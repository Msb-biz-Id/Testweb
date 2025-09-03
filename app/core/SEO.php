<?php

namespace App\Core;

/**
 * SEO Helper Class
 * Handles SEO-related operations
 */
class SEO
{
    /**
     * Generate meta tags
     */
    public static function generateMetaTags($data = [])
    {
        $defaults = [
            'title' => 'Testweb Jersey - Jersey Berkualitas Tinggi',
            'description' => 'Temukan jersey berkualitas tinggi dengan desain terbaik di Testweb Jersey',
            'keywords' => 'jersey, baju olahraga, jersey sepak bola, jersey basket',
            'image' => '/assets/images/og-image.jpg',
            'url' => self::getCurrentUrl(),
            'type' => 'website',
            'site_name' => 'Testweb Jersey'
        ];
        
        $meta = array_merge($defaults, $data);
        
        $tags = [];
        
        // Basic meta tags
        $tags[] = '<title>' . htmlspecialchars($meta['title']) . '</title>';
        $tags[] = '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">';
        $tags[] = '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">';
        $tags[] = '<meta name="author" content="' . htmlspecialchars($meta['site_name']) . '">';
        $tags[] = '<meta name="robots" content="index, follow">';
        $tags[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        
        // Open Graph tags
        $tags[] = '<meta property="og:title" content="' . htmlspecialchars($meta['title']) . '">';
        $tags[] = '<meta property="og:description" content="' . htmlspecialchars($meta['description']) . '">';
        $tags[] = '<meta property="og:image" content="' . htmlspecialchars($meta['image']) . '">';
        $tags[] = '<meta property="og:url" content="' . htmlspecialchars($meta['url']) . '">';
        $tags[] = '<meta property="og:type" content="' . htmlspecialchars($meta['type']) . '">';
        $tags[] = '<meta property="og:site_name" content="' . htmlspecialchars($meta['site_name']) . '">';
        
        // Twitter Card tags
        $tags[] = '<meta name="twitter:card" content="summary_large_image">';
        $tags[] = '<meta name="twitter:title" content="' . htmlspecialchars($meta['title']) . '">';
        $tags[] = '<meta name="twitter:description" content="' . htmlspecialchars($meta['description']) . '">';
        $tags[] = '<meta name="twitter:image" content="' . htmlspecialchars($meta['image']) . '">';
        
        // Canonical URL
        $tags[] = '<link rel="canonical" href="' . htmlspecialchars($meta['url']) . '">';
        
        // Language
        $tags[] = '<meta http-equiv="content-language" content="id">';
        
        // Additional meta tags
        if (isset($meta['published_time'])) {
            $tags[] = '<meta property="article:published_time" content="' . $meta['published_time'] . '">';
        }
        
        if (isset($meta['modified_time'])) {
            $tags[] = '<meta property="article:modified_time" content="' . $meta['modified_time'] . '">';
        }
        
        if (isset($meta['author'])) {
            $tags[] = '<meta property="article:author" content="' . htmlspecialchars($meta['author']) . '">';
        }
        
        return implode("\n    ", $tags);
    }

    /**
     * Generate structured data (JSON-LD)
     */
    public static function generateStructuredData($type, $data)
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => $type
        ];
        
        switch ($type) {
            case 'Organization':
                $structuredData = array_merge($structuredData, [
                    'name' => $data['name'] ?? 'Testweb Jersey',
                    'url' => $data['url'] ?? self::getCurrentUrl(),
                    'logo' => $data['logo'] ?? '/assets/images/logo.png',
                    'description' => $data['description'] ?? 'Jersey berkualitas tinggi dengan desain terbaik',
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => $data['phone'] ?? '+62-812-3456-7890',
                        'contactType' => 'customer service',
                        'availableLanguage' => 'Indonesian'
                    ],
                    'sameAs' => $data['social_media'] ?? []
                ]);
                break;
                
            case 'Product':
                $structuredData = array_merge($structuredData, [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'image' => $data['images'] ?? [],
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => 'Testweb Jersey'
                    ],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $data['price'],
                        'priceCurrency' => 'IDR',
                        'availability' => $data['availability'] ?? 'https://schema.org/InStock',
                        'url' => $data['url']
                    ]
                ]);
                break;
                
            case 'Article':
                $structuredData = array_merge($structuredData, [
                    'headline' => $data['title'],
                    'description' => $data['description'],
                    'image' => $data['image'],
                    'author' => [
                        '@type' => 'Person',
                        'name' => $data['author'] ?? 'Admin'
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'Testweb Jersey',
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => '/assets/images/logo.png'
                        ]
                    ],
                    'datePublished' => $data['published_time'],
                    'dateModified' => $data['modified_time'] ?? $data['published_time']
                ]);
                break;
                
            case 'BreadcrumbList':
                $structuredData['itemListElement'] = [];
                foreach ($data['items'] as $index => $item) {
                    $structuredData['itemListElement'][] = [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $item['name'],
                        'item' => $item['url']
                    ];
                }
                break;
        }
        
        return '<script type="application/ld+json">' . json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    /**
     * Generate sitemap
     */
    public static function generateSitemap($baseUrl)
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Static pages
        $staticPages = [
            ['url' => $baseUrl, 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => $baseUrl . '/produk', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => $baseUrl . '/blog', 'priority' => '0.8', 'changefreq' => 'weekly']
        ];
        
        foreach ($staticPages as $page) {
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . htmlspecialchars($page['url']) . '</loc>' . "\n";
            $sitemap .= '    <priority>' . $page['priority'] . '</priority>' . "\n";
            $sitemap .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
            $sitemap .= '  </url>' . "\n";
        }
        
        $sitemap .= '</urlset>';
        
        return $sitemap;
    }

    /**
     * Generate robots.txt
     */
    public static function generateRobotsTxt($baseUrl)
    {
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
        
        return $robots;
    }

    /**
     * Get current URL
     */
    private static function getCurrentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        return $protocol . '://' . $host . $uri;
    }

    /**
     * Generate breadcrumb data
     */
    public static function generateBreadcrumb($items)
    {
        $breadcrumb = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];
        
        foreach ($items as $index => $item) {
            $breadcrumb['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }
        
        return $breadcrumb;
    }

    /**
     * Optimize image for SEO
     */
    public static function optimizeImageForSEO($imagePath, $alt, $title = null)
    {
        return [
            'src' => $imagePath,
            'alt' => $alt,
            'title' => $title ?: $alt,
            'loading' => 'lazy',
            'width' => 'auto',
            'height' => 'auto'
        ];
    }
}