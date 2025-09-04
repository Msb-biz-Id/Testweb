<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Configuration;

/**
 * Product Controller
 * Handles product display and operations
 */
class ProductController extends Controller
{
    private $productModel;
    private $configModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->configModel = new Configuration();
    }

    /**
     * Display products listing
     */
    public function index()
    {
        $page = (int) $this->request->get('page', 1);
        $search = $this->request->get('search', '');
        $featured = $this->request->get('featured', '');
        
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        if ($featured) {
            $filters['featured'] = true;
        }
        
        $products = $this->productModel->getPaginated($page, 12, $filters);
        $config = $this->configModel->getAll();
        
        $data = [
            'products' => $products,
            'config' => $config,
            'pageTitle' => 'Produk - ' . ($config['site_name'] ?? 'Testweb Jersey'),
            'pageDescription' => 'Lihat koleksi jersey berkualitas tinggi kami'
        ];
        
        $this->render('frontend/product/index', $data);
    }

    /**
     * Display single product
     */
    public function show($slug)
    {
        $product = $this->productModel->findBySlug($slug);
        
        if (!$product) {
            $this->redirect('/produk', 404);
            return;
        }
        
        // Get product images
        $images = $this->productModel->getImages($product['id']);
        
        // Get related products
        $relatedProducts = $this->productModel->getRelated($product['id'], 4);
        
        $config = $this->configModel->getAll();
        
        $data = [
            'product' => $product,
            'images' => $images,
            'relatedProducts' => $relatedProducts,
            'config' => $config,
            'pageTitle' => $product['meta_title'] ?: $product['name'] . ' - ' . ($config['site_name'] ?? 'Testweb Jersey'),
            'pageDescription' => $product['meta_description'] ?: $product['short_description'] ?: 'Jersey berkualitas tinggi'
        ];
        
        $this->render('frontend/product/show', $data);
    }
}