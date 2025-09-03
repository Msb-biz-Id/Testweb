<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Configuration;

/**
 * Blog Controller
 * Handles blog posts display and operations
 */
class BlogController extends Controller
{
    private $postModel;
    private $categoryModel;
    private $tagModel;
    private $configModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->configModel = new Configuration();
    }

    /**
     * Display blog posts listing
     */
    public function index()
    {
        $page = (int) $this->request->get('page', 1);
        $search = $this->request->get('search', '');
        $category = $this->request->get('category', '');
        
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        if ($category) {
            $filters['category'] = $category;
        }
        
        $posts = $this->postModel->getPaginated($page, 10, $filters);
        $categories = $this->categoryModel->getWithPostCount();
        $recentPosts = $this->postModel->getRecent(5);
        
        $config = $this->configModel->getAll();
        
        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
            'config' => $config,
            'pageTitle' => 'Blog - ' . ($config['site_name'] ?? 'Testweb Jersey'),
            'pageDescription' => 'Artikel dan tips seputar jersey dan fashion'
        ];
        
        $this->render('frontend/blog/index', $data);
    }

    /**
     * Display single blog post
     */
    public function show($slug)
    {
        $post = $this->postModel->findBySlug($slug);
        
        if (!$post) {
            $this->redirect('/blog', 404);
            return;
        }
        
        // Get post tags
        $tags = $this->postModel->getTags($post['id']);
        
        // Get related posts
        $relatedPosts = $this->postModel->getRelated($post['id'], 4);
        
        // Get post comments
        $comments = $this->postModel->getComments($post['id']);
        
        $config = $this->configModel->getAll();
        
        $data = [
            'post' => $post,
            'tags' => $tags,
            'relatedPosts' => $relatedPosts,
            'comments' => $comments,
            'config' => $config,
            'pageTitle' => $post['meta_title'] ?: $post['title'] . ' - ' . ($config['site_name'] ?? 'Testweb Jersey'),
            'pageDescription' => $post['meta_description'] ?: $post['excerpt'] ?: 'Artikel menarik seputar jersey'
        ];
        
        $this->render('frontend/blog/show', $data);
    }

    /**
     * Add comment to post
     */
    public function addComment($slug)
    {
        $post = $this->postModel->findBySlug($slug);
        
        if (!$post) {
            $this->json(['success' => false, 'message' => 'Post not found'], 404);
            return;
        }
        
        // Validate input
        $errors = $this->request->validate([
            'author_name' => 'required|min:2|max:100',
            'author_email' => 'required|email',
            'content' => 'required|min:10|max:1000'
        ]);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
            return;
        }
        
        // Check reCAPTCHA if enabled
        $config = $this->configModel->getAll();
        if ($config['recaptcha_enabled'] ?? false) {
            $recaptchaResponse = $this->request->post('g-recaptcha-response');
            if (!$this->verifyRecaptcha($recaptchaResponse, $config['recaptcha_secret_key'])) {
                $this->json(['success' => false, 'message' => 'reCAPTCHA verification failed'], 422);
                return;
            }
        }
        
        // Add comment
        $commentData = [
            'author_name' => $this->request->post('author_name'),
            'author_email' => $this->request->post('author_email'),
            'content' => $this->request->post('content')
        ];
        
        $result = $this->postModel->addComment($post['id'], $commentData);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Komentar berhasil ditambahkan. Menunggu persetujuan admin.']);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal menambahkan komentar'], 500);
        }
    }

    /**
     * Verify reCAPTCHA
     */
    private function verifyRecaptcha($response, $secretKey)
    {
        if (empty($response) || empty($secretKey)) {
            return false;
        }
        
        $data = [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => $this->request->ip()
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        
        if ($result === false) {
            return false;
        }
        
        $result = json_decode($result, true);
        return isset($result['success']) && $result['success'] === true;
    }
}