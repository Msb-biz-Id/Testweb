<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Post;
use App\Models\Page;
use App\Core\SEO;

/**
 * Sitemap Controller
 * Generates XML sitemap for SEO
 */
class SitemapController extends Controller
{
    private $productModel;
    private $postModel;
    private $pageModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->postModel = new Post();
        $this->pageModel = new Page();
    }

    /**
     * Generate XML sitemap
     */
    public function index()
    {
        $baseUrl = $this->config('url');
        
        header('Content-Type: application/xml; charset=utf-8');
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Static pages
        $staticPages = [
            ['url' => $baseUrl, 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => $baseUrl . '/produk', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => $baseUrl . '/blog', 'priority' => '0.8', 'changefreq' => 'weekly']
        ];
        
        foreach ($staticPages as $page) {
            $sitemap .= $this->generateUrlElement($page);
        }
        
        // Product pages
        $products = $this->productModel->getPublished();
        foreach ($products as $product) {
            $sitemap .= $this->generateUrlElement([
                'url' => $baseUrl . '/produk/' . $product['slug'],
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => $product['updated_at']
            ]);
        }
        
        // Blog posts
        $posts = $this->postModel->getPublished();
        foreach ($posts as $post) {
            $sitemap .= $this->generateUrlElement([
                'url' => $baseUrl . '/blog/' . $post['slug'],
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $post['updated_at']
            ]);
        }
        
        // Dynamic pages
        $pages = $this->pageModel->getPublished();
        foreach ($pages as $page) {
            $sitemap .= $this->generateUrlElement([
                'url' => $baseUrl . '/page/' . $page['slug'],
                'priority' => '0.6',
                'changefreq' => 'monthly',
                'lastmod' => $page['updated_at']
            ]);
        }
        
        $sitemap .= '</urlset>';
        
        echo $sitemap;
    }

    /**
     * Generate robots.txt
     */
    public function robots()
    {
        $baseUrl = $this->config('url');
        
        header('Content-Type: text/plain; charset=utf-8');
        
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /storage/\n";
        $robots .= "Disallow: /config/\n";
        $robots .= "Disallow: /app/\n";
        $robots .= "Disallow: /database/\n";
        $robots .= "Disallow: /*.sql\n";
        $robots .= "Disallow: /*.env\n";
        $robots .= "Disallow: /install.php\n";
        $robots .= "\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap.xml\n";
        
        echo $robots;
    }

    /**
     * Generate URL element for sitemap
     */
    private function generateUrlElement($data)
    {
        $url = "  <url>\n";
        $url .= "    <loc>" . htmlspecialchars($data['url']) . "</loc>\n";
        
        if (isset($data['lastmod'])) {
            $url .= "    <lastmod>" . date('Y-m-d', strtotime($data['lastmod'])) . "</lastmod>\n";
        }
        
        if (isset($data['changefreq'])) {
            $url .= "    <changefreq>" . $data['changefreq'] . "</changefreq>\n";
        }
        
        if (isset($data['priority'])) {
            $url .= "    <priority>" . $data['priority'] . "</priority>\n";
        }
        
        $url .= "  </url>\n";
        
        return $url;
    }
}