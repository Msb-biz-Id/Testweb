<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Post;
use App\Models\Configuration;

/**
 * Search Controller
 * Handles global search functionality
 */
class SearchController extends Controller
{
    private $productModel;
    private $postModel;
    private $configModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->postModel = new Post();
        $this->configModel = new Configuration();
    }

    /**
     * Display search results
     */
    public function index()
    {
        $query = $this->request->get('q', '');
        $type = $this->request->get('type', 'all'); // all, products, posts
        $page = (int) $this->request->get('page', 1);
        
        $results = [
            'products' => [],
            'posts' => [],
            'total' => 0
        ];
        
        if (!empty($query)) {
            if ($type === 'all' || $type === 'products') {
                $productResults = $this->productModel->search($query, 20);
                $results['products'] = $productResults;
            }
            
            if ($type === 'all' || $type === 'posts') {
                $postResults = $this->postModel->search($query, 20);
                $results['posts'] = $postResults;
            }
            
            $results['total'] = count($results['products']) + count($results['posts']);
        }
        
        $config = $this->configModel->getAll();
        
        $data = [
            'query' => $query,
            'type' => $type,
            'results' => $results,
            'config' => $config,
            'pageTitle' => 'Pencarian: ' . $query . ' - ' . ($config['site_name'] ?? 'Testweb Jersey'),
            'pageDescription' => 'Hasil pencarian untuk: ' . $query
        ];
        
        $this->render('frontend/search/index', $data);
    }
}