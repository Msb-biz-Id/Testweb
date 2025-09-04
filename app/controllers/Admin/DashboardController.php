<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Post;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\ClientLogo;
use App\Models\Configuration;

/**
 * Admin Dashboard Controller
 * Handles admin dashboard
 */
class DashboardController extends Controller
{
    private $productModel;
    private $postModel;
    private $pageModel;
    private $testimonialModel;
    private $clientLogoModel;
    private $configModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        
        $this->productModel = new Product();
        $this->postModel = new Post();
        $this->pageModel = new Page();
        $this->testimonialModel = new Testimonial();
        $this->clientLogoModel = new ClientLogo();
        $this->configModel = new Configuration();
    }

    /**
     * Display dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'products' => $this->productModel->count(['is_published' => 1]),
            'posts' => $this->postModel->count(['is_published' => 1]),
            'pages' => $this->pageModel->count(['is_published' => 1]),
            'testimonials' => $this->testimonialModel->count(['is_published' => 1]),
            'client_logos' => $this->clientLogoModel->count(['is_published' => 1])
        ];
        
        // Get recent activities
        $recentProducts = $this->productModel->fetchAll(
            "SELECT * FROM products ORDER BY created_at DESC LIMIT 5"
        );
        
        $recentPosts = $this->postModel->fetchAll(
            "SELECT p.*, u.full_name as author_name FROM posts p 
             LEFT JOIN users u ON p.author_id = u.id 
             ORDER BY p.created_at DESC LIMIT 5"
        );
        
        $config = $this->configModel->getAll();
        
        $data = [
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'recentPosts' => $recentPosts,
            'config' => $config,
            'pageTitle' => 'Dashboard - Admin Testweb Jersey'
        ];
        
        $this->render('admin/dashboard/index', $data);
    }
}