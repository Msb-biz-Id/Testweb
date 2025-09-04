<?php

namespace App\Controllers\Admin;

use App\Core\SEORankMath;

/**
 * Admin SEO Controller
 * Handles SEO management in admin panel
 */
class SEOController extends \App\Controllers\Controller
{
    private $seoRankMath;
    
    public function __construct()
    {
        parent::__construct();
        $this->seoRankMath = new SEORankMath();
    }
    
    /**
     * Display SEO dashboard
     */
    public function dashboard()
    {
        $analytics = $this->seoRankMath->getDashboardAnalytics();
        
        $this->render('admin/seo/dashboard', [
            'analytics' => $analytics,
            'pageTitle' => 'SEO Dashboard',
            'breadcrumbs' => [
                ['title' => 'SEO Dashboard']
            ]
        ]);
    }
    
    /**
     * Display SEO analytics
     */
    public function analytics()
    {
        $this->render('admin/seo/analytics', [
            'pageTitle' => 'SEO Analytics',
            'breadcrumbs' => [
                ['title' => 'SEO', 'url' => '/admin/seo/dashboard'],
                ['title' => 'Analytics']
            ]
        ]);
    }
    
    /**
     * Display redirect manager
     */
    public function redirects()
    {
        $redirects = $this->seoRankMath->manageRedirects();
        
        $this->render('admin/seo/redirects', [
            'redirects' => $redirects,
            'pageTitle' => 'Redirect Manager',
            'breadcrumbs' => [
                ['title' => 'SEO', 'url' => '/admin/seo/dashboard'],
                ['title' => 'Redirects']
            ]
        ]);
    }
}