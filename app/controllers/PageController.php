<?php

namespace App\Controllers;

use App\Models\Page;
use App\Models\Configuration;

/**
 * Page Controller
 * Handles dynamic page display
 */
class PageController extends Controller
{
    private $pageModel;
    private $configModel;

    public function __construct()
    {
        parent::__construct();
        $this->pageModel = new Page();
        $this->configModel = new Configuration();
    }

    /**
     * Display dynamic page
     */
    public function show($slug)
    {
        $page = $this->pageModel->findBySlug($slug);
        
        if (!$page || !$page['is_published']) {
            $this->redirect('/', 404);
            return;
        }
        
        $config = $this->configModel->getAll();
        
        $data = [
            'page' => $page,
            'config' => $config,
            'pageTitle' => $page['meta_title'] ?: $page['title'] . ' - ' . ($config['site_name'] ?? 'Testweb Jersey'),
            'pageDescription' => $page['meta_description'] ?: 'Halaman ' . $page['title']
        ];
        
        $this->render('frontend/page/show', $data);
    }
}