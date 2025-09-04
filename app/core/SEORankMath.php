<?php

namespace App\Core;

/**
 * SEO RankMath Equivalent Class
 * Complete SEO management system inspired by RankMath WordPress plugin
 */
class SEORankMath
{
    private $db;
    private $config;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../../config/app.php';
    }
    
    /**
     * RankMath-like SEO Score Analysis
     */
    public function analyzeSEOScore($content, $meta = [])
    {
        $score = 0;
        $maxScore = 100;
        $issues = [];
        $suggestions = [];
        $goodPractices = [];
        
        // Title Analysis (15 points)
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
                $goodPractices[] = 'Title length is optimal';
            }
        }
        
        // Meta Description Analysis (15 points)
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
                $goodPractices[] = 'Meta description length is optimal';
            }
        }
        
        // Content Analysis (20 points)
        $contentLength = strlen(strip_tags($content));
        if ($contentLength < 300) {
            $issues[] = 'Content too short (less than 300 words)';
        } else {
            $score += 20;
            $goodPractices[] = 'Content length is sufficient';
        }
        
        // Heading Structure (10 points)
        $h1Count = substr_count(strtolower($content), '<h1>');
        $h2Count = substr_count(strtolower($content), '<h2>');
        
        if ($h1Count === 0) {
            $issues[] = 'Missing H1 heading';
        } elseif ($h1Count > 1) {
            $issues[] = 'Multiple H1 headings found';
        } else {
            $score += 10;
            $goodPractices[] = 'Proper H1 heading structure';
        }
        
        if ($h2Count > 0) {
            $score += 5;
            $goodPractices[] = 'Good heading hierarchy with H2 tags';
        }
        
        // Image Alt Tags (10 points)
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
                $goodPractices[] = 'Most images have alt tags';
            }
        }
        
        // Internal Links (10 points)
        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $content, $links);
        $internalLinks = 0;
        foreach ($links[1] as $link) {
            if (strpos($link, $this->config['site_url']) !== false || strpos($link, '/') === 0) {
                $internalLinks++;
            }
        }
        
        if ($internalLinks > 0) {
            $score += 10;
            $goodPractices[] = 'Good internal linking';
        } else {
            $suggestions[] = 'Add internal links to improve SEO';
        }
        
        // Keyword Density (10 points)
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
                $goodPractices[] = 'Good keyword density';
            } else {
                $suggestions[] = 'Optimize keyword density (0.5-3%)';
            }
        }
        
        // Page Speed Suggestions
        $suggestions[] = 'Optimize images for faster loading';
        $suggestions[] = 'Minify CSS and JavaScript';
        $suggestions[] = 'Enable browser caching';
        $suggestions[] = 'Use a Content Delivery Network (CDN)';
        
        // Mobile Optimization
        if (strpos($content, 'viewport') !== false) {
            $score += 5;
            $goodPractices[] = 'Mobile viewport meta tag present';
        } else {
            $issues[] = 'Missing mobile viewport meta tag';
        }
        
        return [
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => round(($score / $maxScore) * 100),
            'grade' => $this->getSEOGrade($score),
            'issues' => $issues,
            'suggestions' => $suggestions,
            'good_practices' => $goodPractices,
            'color' => $this->getScoreColor($score)
        ];
    }
    
    /**
     * RankMath-like Schema Generator
     */
    public function generateAdvancedSchema($type = 'website', $data = [])
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
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => $siteName,
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => $baseUrl . '/assets/images/logo.png'
                        ]
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
                    ],
                    'foundingDate' => '2020',
                    'numberOfEmployees' => '10-50'
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
                        'availability' => 'https://schema.org/InStock',
                        'seller' => [
                            '@type' => 'Organization',
                            'name' => $siteName
                        ]
                    ],
                    'aggregateRating' => [
                        '@type' => 'AggregateRating',
                        'ratingValue' => $data['rating'] ?? '5',
                        'reviewCount' => $data['review_count'] ?? '1'
                    ],
                    'category' => $data['category'] ?? 'Sports Apparel'
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
                    'dateModified' => $data['modified_date'] ?? date('c'),
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $data['url'] ?? $baseUrl
                    ]
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
                    ],
                    'workHours' => '40 hours per week',
                    'benefits' => $data['benefits'] ?? 'Health insurance, paid time off'
                ];
                
            case 'faq':
                $faqItems = [];
                foreach ($data['faqs'] as $faq) {
                    $faqItems[] = [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer']
                        ]
                    ];
                }
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faqItems
                ];
                
            default:
                return [];
        }
    }
    
    /**
     * RankMath-like Redirect Manager
     */
    public function manageRedirects()
    {
        // This would typically manage 301 redirects
        // For now, we'll return a basic structure
        return [
            'redirects' => [
                '/old-page' => '/new-page',
                '/old-product' => '/products/new-product'
            ],
            'broken_links' => [],
            '404_errors' => []
        ];
    }
    
    /**
     * RankMath-like Content Optimizer
     */
    public function optimizeContent($content, $targetKeyword = '')
    {
        $optimization = [
            'keyword_density' => 0,
            'keyword_frequency' => 0,
            'readability_score' => 0,
            'suggestions' => []
        ];
        
        if (!empty($targetKeyword)) {
            $keywordCount = substr_count(strtolower($content), strtolower($targetKeyword));
            $wordCount = str_word_count(strip_tags($content));
            $optimization['keyword_frequency'] = $keywordCount;
            $optimization['keyword_density'] = ($keywordCount / $wordCount) * 100;
            
            if ($optimization['keyword_density'] < 0.5) {
                $optimization['suggestions'][] = 'Increase keyword density';
            } elseif ($optimization['keyword_density'] > 3) {
                $optimization['suggestions'][] = 'Reduce keyword density';
            }
        }
        
        // Readability analysis
        $sentences = preg_split('/[.!?]+/', strip_tags($content));
        $words = str_word_count(strip_tags($content));
        $avgWordsPerSentence = $words / count($sentences);
        
        if ($avgWordsPerSentence > 20) {
            $optimization['suggestions'][] = 'Use shorter sentences for better readability';
        }
        
        return $optimization;
    }
    
    /**
     * RankMath-like Image Optimizer
     */
    public function optimizeImages($content)
    {
        $images = [];
        preg_match_all('/<img[^>]+>/i', $content, $matches);
        
        foreach ($matches[0] as $img) {
            $imageData = [
                'has_alt' => strpos($img, 'alt=') !== false,
                'has_title' => strpos($img, 'title=') !== false,
                'is_optimized' => true
            ];
            
            if (!$imageData['has_alt']) {
                $imageData['is_optimized'] = false;
            }
            
            $images[] = $imageData;
        }
        
        return $images;
    }
    
    /**
     * Get SEO Grade
     */
    private function getSEOGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
    
    /**
     * Get Score Color
     */
    private function getScoreColor($score)
    {
        if ($score >= 80) return 'success';
        if ($score >= 60) return 'warning';
        return 'danger';
    }
    
    /**
     * RankMath-like Dashboard Analytics
     */
    public function getDashboardAnalytics()
    {
        return [
            'seo_score' => $this->getOverallSEOScore(),
            'indexed_pages' => $this->getIndexedPagesCount(),
            'broken_links' => $this->getBrokenLinksCount(),
            'redirects' => $this->getRedirectsCount(),
            'schema_markup' => $this->getSchemaMarkupCount(),
            'recent_issues' => $this->getRecentIssues()
        ];
    }
    
    private function getOverallSEOScore()
    {
        // This would calculate overall site SEO score
        return 85;
    }
    
    private function getIndexedPagesCount()
    {
        // This would count indexed pages
        return 25;
    }
    
    private function getBrokenLinksCount()
    {
        // This would count broken links
        return 2;
    }
    
    private function getRedirectsCount()
    {
        // This would count active redirects
        return 5;
    }
    
    private function getSchemaMarkupCount()
    {
        // This would count pages with schema markup
        return 20;
    }
    
    private function getRecentIssues()
    {
        return [
            'Missing meta descriptions on 3 pages',
            '2 images without alt tags',
            '1 broken internal link'
        ];
    }
}