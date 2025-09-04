<?php

namespace App\Core;

/**
 * SEO Management Class - RankMath Inspired
 * Comprehensive SEO optimization for Testweb Jersey
 */
class SEO
{
    private $db;
    private $config;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../../config/app.php';
    }
    
    /**
     * Generate comprehensive meta tags for any page
     */
    public function generateMetaTags($pageData = [])
    {
        $defaults = [
            'title' => $this->config['meta_title_default'] ?? 'Testweb Jersey - Premium Jersey Collection',
            'description' => $this->config['meta_description_default'] ?? 'Jersey berkualitas tinggi dengan desain terbaik untuk sepak bola, basket, dan olahraga lainnya.',
            'keywords' => 'jersey, baju olahraga, jersey sepak bola, jersey basket, jersey custom, jersey premium',
            'image' => $this->config['site_url'] . '/assets/images/og-image.jpg',
            'url' => $this->getCurrentUrl(),
            'type' => 'website',
            'author' => 'Testweb Jersey',
            'robots' => 'index, follow',
            'canonical' => $this->getCurrentUrl(),
            'locale' => 'id_ID',
            'site_name' => $this->config['site_name'] ?? 'Testweb Jersey'
        ];
        
        $meta = array_merge($defaults, $pageData);
        
        return [
            'title' => $this->optimizeTitle($meta['title']),
            'description' => $this->optimizeDescription($meta['description']),
            'keywords' => $this->optimizeKeywords($meta['keywords']),
            'image' => $meta['image'],
            'url' => $meta['url'],
            'type' => $meta['type'],
            'author' => $meta['author'],
            'robots' => $meta['robots'],
            'canonical' => $meta['canonical'],
            'locale' => $meta['locale'],
            'site_name' => $meta['site_name']
        ];
    }
    
    /**
     * Generate structured data (JSON-LD)
     */
    public function generateStructuredData($type = 'website', $data = [])
    {
        $baseUrl = $this->config['site_url'];
        $siteName = $this->config['site_name'] ?? 'Testweb Jersey';
        
        switch ($type) {
            case 'website':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => $siteName,
                    'url' => $baseUrl,
                    'description' => $this->config['meta_description_default'] ?? '',
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => $baseUrl . '/search?q={search_term_string}',
                        'query-input' => 'required name=search_term_string'
                    ]
                ];
                
            case 'organization':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'url' => $baseUrl,
                    'logo' => $baseUrl . '/assets/images/logo.png',
                    'description' => $this->config['meta_description_default'] ?? '',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressCountry' => 'ID',
                        'addressLocality' => 'Jakarta',
                        'addressRegion' => 'DKI Jakarta'
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => '+62-812-3456-7890',
                        'contactType' => 'customer service',
                        'availableLanguage' => 'Indonesian'
                    ],
                    'sameAs' => [
                        'https://www.facebook.com/testwebjersey',
                        'https://www.instagram.com/testwebjersey',
                        'https://www.twitter.com/testwebjersey'
                    ]
                ];
                
            case 'product':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Product',
                    'name' => $data['name'] ?? '',
                    'description' => $data['description'] ?? '',
                    'image' => $data['image'] ?? '',
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $siteName
                    ],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $data['price'] ?? '0',
                        'priceCurrency' => 'IDR',
                        'availability' => 'https://schema.org/InStock'
                    ],
                    'aggregateRating' => [
                        '@type' => 'AggregateRating',
                        'ratingValue' => $data['rating'] ?? '5',
                        'reviewCount' => $data['review_count'] ?? '1'
                    ]
                ];
                
            case 'article':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $data['title'] ?? '',
                    'description' => $data['description'] ?? '',
                    'image' => $data['image'] ?? '',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $data['author'] ?? 'Admin'
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => $siteName,
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => $baseUrl . '/assets/images/logo.png'
                        ]
                    ],
                    'datePublished' => $data['published_date'] ?? date('c'),
                    'dateModified' => $data['modified_date'] ?? date('c')
                ];
                
            case 'job_posting':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'JobPosting',
                    'title' => $data['title'] ?? '',
                    'description' => $data['description'] ?? '',
                    'datePosted' => $data['date_posted'] ?? date('c'),
                    'validThrough' => $data['valid_through'] ?? date('c', strtotime('+30 days')),
                    'employmentType' => $data['employment_type'] ?? 'FULL_TIME',
                    'hiringOrganization' => [
                        '@type' => 'Organization',
                        'name' => $siteName,
                        'sameAs' => $baseUrl
                    ],
                    'jobLocation' => [
                        '@type' => 'Place',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressLocality' => 'Jakarta',
                            'addressRegion' => 'DKI Jakarta',
                            'addressCountry' => 'ID'
                        ]
                    ],
                    'baseSalary' => [
                        '@type' => 'MonetaryAmount',
                        'currency' => 'IDR',
                        'value' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => $data['salary_min'] ?? '0',
                            'maxValue' => $data['salary_max'] ?? '0',
                            'unitText' => 'MONTH'
                        ]
                    ]
                ];
                
            default:
                return [];
        }
    }
    
    /**
     * Generate XML Sitemap
     */
    public function generateSitemap()
    {
        $baseUrl = $this->config['site_url'];
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        $sitemap .= $this->addSitemapUrl($baseUrl, '1.0', 'daily');
        
        // Static pages
        $staticPages = [
            '/about' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            '/products' => ['priority' => '0.9', 'changefreq' => 'weekly'],
            '/blog' => ['priority' => '0.8', 'changefreq' => 'weekly'],
            '/careers' => ['priority' => '0.7', 'changefreq' => 'weekly'],
            '/contact' => ['priority' => '0.6', 'changefreq' => 'monthly']
        ];
        
        foreach ($staticPages as $page => $settings) {
            $sitemap .= $this->addSitemapUrl($baseUrl . $page, $settings['priority'], $settings['changefreq']);
        }
        
        // Products
        $products = $this->getProducts();
        foreach ($products as $product) {
            $sitemap .= $this->addSitemapUrl(
                $baseUrl . '/products/' . $product['slug'],
                '0.8',
                'weekly',
                $product['updated_at']
            );
        }
        
        // Blog posts
        $posts = $this->getPosts();
        foreach ($posts as $post) {
            $sitemap .= $this->addSitemapUrl(
                $baseUrl . '/blog/' . $post['slug'],
                '0.7',
                'monthly',
                $post['updated_at']
            );
        }
        
        // Career posts
        $careers = $this->getCareers();
        foreach ($careers as $career) {
            $sitemap .= $this->addSitemapUrl(
                $baseUrl . '/careers/' . $career['slug'],
                '0.6',
                'weekly',
                $career['updated_at']
            );
        }
        
        $sitemap .= '</urlset>';
        
        return $sitemap;
    }
    
    /**
     * Generate robots.txt
     */
    public function generateRobotsTxt()
    {
        $baseUrl = $this->config['site_url'];
        
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /storage/\n";
        $robots .= "Disallow: /config/\n";
        $robots .= "Disallow: /*.sql$\n";
        $robots .= "Disallow: /*.log$\n";
        $robots .= "\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap.xml\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap-products.xml\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap-blog.xml\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap-careers.xml\n";
        
        return $robots;
    }
    
    /**
     * Analyze page SEO score
     */
    public function analyzeSEOScore($content, $meta = [])
    {
        $score = 0;
        $maxScore = 100;
        $issues = [];
        $suggestions = [];
        
        // Title analysis
        if (empty($meta['title'])) {
            $issues[] = 'Missing page title';
        } else {
            $titleLength = strlen($meta['title']);
            if ($titleLength < 30) {
                $issues[] = 'Title too short (less than 30 characters)';
            } elseif ($titleLength > 60) {
                $issues[] = 'Title too long (more than 60 characters)';
            } else {
                $score += 15;
            }
        }
        
        // Description analysis
        if (empty($meta['description'])) {
            $issues[] = 'Missing meta description';
        } else {
            $descLength = strlen($meta['description']);
            if ($descLength < 120) {
                $issues[] = 'Meta description too short (less than 120 characters)';
            } elseif ($descLength > 160) {
                $issues[] = 'Meta description too long (more than 160 characters)';
            } else {
                $score += 15;
            }
        }
        
        // Content analysis
        $contentLength = strlen(strip_tags($content));
        if ($contentLength < 300) {
            $issues[] = 'Content too short (less than 300 words)';
        } else {
            $score += 20;
        }
        
        // Heading structure
        if (strpos($content, '<h1>') === false) {
            $issues[] = 'Missing H1 heading';
        } else {
            $score += 10;
        }
        
        // Image alt tags
        preg_match_all('/<img[^>]+>/i', $content, $images);
        $imagesWithAlt = 0;
        foreach ($images[0] as $img) {
            if (strpos($img, 'alt=') !== false) {
                $imagesWithAlt++;
            }
        }
        
        if (count($images[0]) > 0) {
            $altPercentage = ($imagesWithAlt / count($images[0])) * 100;
            if ($altPercentage < 80) {
                $issues[] = 'Some images missing alt tags';
            } else {
                $score += 10;
            }
        }
        
        // Internal links
        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $content, $links);
        $internalLinks = 0;
        foreach ($links[1] as $link) {
            if (strpos($link, $this->config['site_url']) !== false || strpos($link, '/') === 0) {
                $internalLinks++;
            }
        }
        
        if ($internalLinks > 0) {
            $score += 10;
        } else {
            $suggestions[] = 'Add internal links to improve SEO';
        }
        
        // Keyword density
        if (!empty($meta['keywords'])) {
            $keywords = explode(',', $meta['keywords']);
            $keywordDensity = 0;
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                $count = substr_count(strtolower($content), strtolower($keyword));
                $density = ($count / $contentLength) * 100;
                if ($density > 0.5 && $density < 3) {
                    $keywordDensity++;
                }
            }
            
            if ($keywordDensity > 0) {
                $score += 10;
            } else {
                $suggestions[] = 'Optimize keyword density (0.5-3%)';
            }
        }
        
        // Page speed suggestions
        $suggestions[] = 'Optimize images for faster loading';
        $suggestions[] = 'Minify CSS and JavaScript';
        $suggestions[] = 'Enable browser caching';
        
        return [
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => round(($score / $maxScore) * 100),
            'issues' => $issues,
            'suggestions' => $suggestions
        ];
    }
    
    /**
     * Generate breadcrumb schema
     */
    public function generateBreadcrumbSchema($breadcrumbs)
    {
        $baseUrl = $this->config['site_url'];
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];
        
        $position = 1;
        foreach ($breadcrumbs as $breadcrumb) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $breadcrumb['title'],
                'item' => $baseUrl . $breadcrumb['url']
            ];
            $position++;
        }
        
        return $schema;
    }
    
    /**
     * Private helper methods
     */
    private function optimizeTitle($title)
    {
        $title = trim($title);
        $siteName = $this->config['site_name'] ?? 'Testweb Jersey';
        
        // Add site name if not present and title is not too long
        if (strpos($title, $siteName) === false && strlen($title) < 50) {
            $title .= ' - ' . $siteName;
        }
        
        return $title;
    }
    
    private function optimizeDescription($description)
    {
        $description = trim($description);
        
        // Ensure description is within optimal length
        if (strlen($description) > 160) {
            $description = substr($description, 0, 157) . '...';
        }
        
        return $description;
    }
    
    private function optimizeKeywords($keywords)
    {
        if (is_string($keywords)) {
            $keywords = explode(',', $keywords);
        }
        
        $keywords = array_map('trim', $keywords);
        $keywords = array_filter($keywords);
        $keywords = array_unique($keywords);
        
        return implode(', ', array_slice($keywords, 0, 10)); // Max 10 keywords
    }
    
    private function getCurrentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    private function addSitemapUrl($url, $priority, $changefreq, $lastmod = null)
    {
        $xml = "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
        $xml .= "    <priority>" . $priority . "</priority>\n";
        $xml .= "    <changefreq>" . $changefreq . "</changefreq>\n";
        
        if ($lastmod) {
            $xml .= "    <lastmod>" . date('c', strtotime($lastmod)) . "</lastmod>\n";
        }
        
        $xml .= "  </url>\n";
        
        return $xml;
    }
    
    private function getProducts()
    {
        try {
            $stmt = $this->db->prepare("SELECT slug, updated_at FROM products WHERE status = 'active' ORDER BY updated_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getPosts()
    {
        try {
            $stmt = $this->db->prepare("SELECT slug, updated_at FROM posts WHERE status = 'published' ORDER BY updated_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getCareers()
    {
        try {
            $stmt = $this->db->prepare("SELECT slug, updated_at FROM careers WHERE status = 'active' ORDER BY updated_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
}