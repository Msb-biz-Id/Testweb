<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;

/**
 * Admin Product Controller
 * Handles product management
 */
class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->productModel = new Product();
    }

    /**
     * Display products listing
     */
    public function index()
    {
        $page = (int) $this->request->get('page', 1);
        $search = $this->request->get('search', '');
        
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        
        $products = $this->productModel->getPaginated($page, 15, $filters);
        
        $data = [
            'products' => $products,
            'pageTitle' => 'Kelola Produk - Admin'
        ];
        
        $this->render('admin/products/index', $data);
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Tambah Produk - Admin'
        ];
        
        $this->render('admin/products/create', $data);
    }

    /**
     * Store new product
     */
    public function store()
    {
        // Validate input
        $errors = $this->request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'price' => 'required|numeric'
        ]);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
            return;
        }
        
        $data = [
            'name' => $this->request->post('name'),
            'description' => $this->request->post('description'),
            'short_description' => $this->request->post('short_description'),
            'price' => $this->request->post('price'),
            'sale_price' => $this->request->post('sale_price'),
            'sku' => $this->request->post('sku'),
            'stock_quantity' => $this->request->post('stock_quantity', 0),
            'is_featured' => $this->request->post('is_featured') ? 1 : 0,
            'is_published' => $this->request->post('is_published') ? 1 : 0,
            'marketplace_url' => $this->request->post('marketplace_url'),
            'meta_title' => $this->request->post('meta_title'),
            'meta_description' => $this->request->post('meta_description')
        ];
        
        $productId = $this->productModel->createProduct($data);
        
        if ($productId) {
            $this->json(['success' => true, 'message' => 'Produk berhasil ditambahkan', 'id' => $productId]);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal menambahkan produk'], 500);
        }
    }

    /**
     * Show edit product form
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->redirect('/admin/products', 404);
            return;
        }
        
        $images = $this->productModel->getImages($id);
        
        $data = [
            'product' => $product,
            'images' => $images,
            'pageTitle' => 'Edit Produk - Admin'
        ];
        
        $this->render('admin/products/edit', $data);
    }

    /**
     * Update product
     */
    public function update($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
            return;
        }
        
        // Validate input
        $errors = $this->request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'price' => 'required|numeric'
        ]);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
            return;
        }
        
        $data = [
            'name' => $this->request->post('name'),
            'description' => $this->request->post('description'),
            'short_description' => $this->request->post('short_description'),
            'price' => $this->request->post('price'),
            'sale_price' => $this->request->post('sale_price'),
            'sku' => $this->request->post('sku'),
            'stock_quantity' => $this->request->post('stock_quantity', 0),
            'is_featured' => $this->request->post('is_featured') ? 1 : 0,
            'is_published' => $this->request->post('is_published') ? 1 : 0,
            'marketplace_url' => $this->request->post('marketplace_url'),
            'meta_title' => $this->request->post('meta_title'),
            'meta_description' => $this->request->post('meta_description')
        ];
        
        $result = $this->productModel->updateProduct($id, $data);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Produk berhasil diperbarui']);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal memperbarui produk'], 500);
        }
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
            return;
        }
        
        $result = $this->productModel->delete($id);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Produk berhasil dihapus']);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal menghapus produk'], 500);
        }
    }
}