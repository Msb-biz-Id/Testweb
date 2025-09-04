<?php

namespace App\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\ClientLogo;
use App\Models\Configuration;

/**
 * Home Controller
 * Handles homepage display with dynamic content
 */
class HomeController extends Controller
{
    private $pageModel;
    private $productModel;
    private $postModel;
    private $testimonialModel;
    private $clientLogoModel;
    private $configModel;

    public function __construct()
    {
        parent::__construct();
        $this->pageModel = new Page();
        $this->productModel = new Product();
        $this->postModel = new Post();
        $this->testimonialModel = new Testimonial();
        $this->clientLogoModel = new ClientLogo();
        $this->configModel = new Configuration();
    }

    /**
     * Display homepage
     */
    public function index()
    {
        // Get homepage content
        $homepage = $this->pageModel->getHomepage();
        
        // Get featured products
        $featuredProducts = $this->productModel->getFeatured(8);
        
        // Get recent posts
        $recentPosts = $this->postModel->getRecent(3);
        
        // Get featured testimonials
        $testimonials = $this->testimonialModel->getFeatured(5);
        
        // Get client logos
        $clientLogos = $this->clientLogoModel->getOrdered();
        
        // Get site configurations
        $config = $this->configModel->getAll();
        
        // Prepare data for view
        $data = [
            'homepage' => $homepage,
            'featuredProducts' => $featuredProducts,
            'recentPosts' => $recentPosts,
            'testimonials' => $testimonials,
            'clientLogos' => $clientLogos,
            'config' => $config,
            'pageTitle' => $config['meta_title_default'] ?? 'Testweb Jersey',
            'pageDescription' => $config['meta_description_default'] ?? 'Jersey berkualitas tinggi dengan desain terbaik'
        ];
        
        $this->render('frontend/home/index', $data);
    }
}