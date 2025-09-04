<?php

namespace App\Controllers;

use App\Models\Career;
use App\Models\JobApplication;
use App\Core\SEO;

/**
 * Career Controller
 * Handles career-related requests
 */
class CareerController extends Controller
{
    private $careerModel;
    private $applicationModel;
    private $seo;
    
    public function __construct()
    {
        parent::__construct();
        $this->careerModel = new Career();
        $this->applicationModel = new JobApplication();
        $this->seo = new SEO();
    }
    
    /**
     * Display careers listing page
     */
    public function index()
    {
        $filters = [
            'status' => 'active',
            'limit' => 12
        ];
        
        // Apply filters from request
        if ($this->request->get('employment_type')) {
            $filters['employment_type'] = $this->request->get('employment_type');
        }
        
        if ($this->request->get('department')) {
            $filters['department'] = $this->request->get('department');
        }
        
        if ($this->request->get('experience_level')) {
            $filters['experience_level'] = $this->request->get('experience_level');
        }
        
        if ($this->request->get('search')) {
            $filters['search'] = $this->request->get('search');
        }
        
        $careers = $this->careerModel->getAll($filters);
        $featuredCareers = $this->careerModel->getFeatured(3);
        $stats = $this->careerModel->getStats();
        
        // SEO Meta Tags
        $seoData = $this->seo->generateMetaTags([
            'title' => 'Karir & Lowongan Kerja - Testweb Jersey',
            'description' => 'Bergabunglah dengan tim Testweb Jersey! Temukan lowongan kerja terbaru di bidang teknologi, marketing, design, dan lainnya. Kembangkan karir Anda bersama kami.',
            'keywords' => 'karir, lowongan kerja, testweb jersey, job vacancy, career opportunity, teknologi, marketing, design',
            'type' => 'website'
        ]);
        
        $structuredData = $this->seo->generateStructuredData('organization');
        
        $this->render('frontend/careers/index', [
            'careers' => $careers,
            'featuredCareers' => $featuredCareers,
            'stats' => $stats,
            'filters' => $filters,
            'seoData' => $seoData,
            'structuredData' => $structuredData,
            'pageTitle' => $seoData['title'],
            'pageDescription' => $seoData['description'],
            'pageKeywords' => $seoData['keywords']
        ]);
    }
    
    /**
     * Display single career detail page
     */
    public function show($slug)
    {
        $career = $this->careerModel->getBySlug($slug);
        
        if (!$career) {
            $this->notFound();
            return;
        }
        
        // Increment view count
        $this->careerModel->incrementViews($career['id']);
        
        // Get related careers
        $relatedCareers = $this->careerModel->getByDepartment($career['department'], 3);
        $relatedCareers = array_filter($relatedCareers, function($c) use ($career) {
            return $c['id'] != $career['id'];
        });
        
        // SEO Meta Tags
        $seoData = $this->seo->generateMetaTags([
            'title' => $career['meta_title'] ?: $career['title'] . ' - Testweb Jersey',
            'description' => $career['meta_description'] ?: substr(strip_tags($career['description']), 0, 160),
            'keywords' => $career['meta_keywords'] ?: $career['title'] . ', ' . $career['department'] . ', ' . $career['employment_type'],
            'type' => 'article',
            'image' => $this->config['site_url'] . '/assets/images/career-default.jpg'
        ]);
        
        $structuredData = $this->seo->generateStructuredData('job_posting', [
            'title' => $career['title'],
            'description' => $career['description'],
            'date_posted' => $career['created_at'],
            'valid_through' => $career['application_deadline'] ?: date('c', strtotime('+30 days')),
            'employment_type' => strtoupper($career['employment_type']),
            'salary_min' => $career['salary_min'],
            'salary_max' => $career['salary_max']
        ]);
        
        $breadcrumbs = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Karir', 'url' => '/careers'],
            ['title' => $career['title'], 'url' => '']
        ];
        
        $breadcrumbSchema = $this->seo->generateBreadcrumbSchema($breadcrumbs);
        
        $this->render('frontend/careers/show', [
            'career' => $career,
            'relatedCareers' => $relatedCareers,
            'seoData' => $seoData,
            'structuredData' => $structuredData,
            'breadcrumbSchema' => $breadcrumbSchema,
            'pageTitle' => $seoData['title'],
            'pageDescription' => $seoData['description'],
            'pageKeywords' => $seoData['keywords'],
            'breadcrumbs' => $breadcrumbs
        ]);
    }
    
    /**
     * Handle job application form submission
     */
    public function apply()
    {
        if ($this->request->isPost()) {
            $careerId = $this->request->post('career_id');
            $email = $this->request->post('email');
            
            // Check if already applied
            if ($this->applicationModel->hasApplied($email, $careerId)) {
                $_SESSION['error'] = 'Anda sudah pernah melamar posisi ini sebelumnya.';
                $this->redirect('/careers/' . $this->request->post('career_slug'));
                return;
            }
            
            // Validate required fields
            $validation = $this->request->validate([
                'career_id' => 'required|numeric',
                'full_name' => 'required|min:2',
                'email' => 'required|email',
                'phone' => 'required|min:10',
                'cover_letter' => 'required|min:50'
            ]);
            
            if ($validation !== true) {
                $_SESSION['error'] = 'Mohon lengkapi semua field yang diperlukan.';
                $this->redirect('/careers/' . $this->request->post('career_slug'));
                return;
            }
            
            // Handle file upload
            $resumeFile = null;
            if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/resumes/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileExtension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['pdf', 'doc', 'docx'];
                
                if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['resume']['tmp_name'], $filePath)) {
                        $resumeFile = $fileName;
                    }
                }
            }
            
            // Prepare application data
            $applicationData = [
                'career_id' => $this->request->post('career_id'),
                'full_name' => $this->request->post('full_name'),
                'email' => $this->request->post('email'),
                'phone' => $this->request->post('phone'),
                'cover_letter' => $this->request->post('cover_letter'),
                'resume_file' => $resumeFile,
                'portfolio_url' => $this->request->post('portfolio_url'),
                'linkedin_url' => $this->request->post('linkedin_url'),
                'expected_salary' => $this->request->post('expected_salary'),
                'availability_date' => $this->request->post('availability_date')
            ];
            
            // Create application
            if ($this->applicationModel->create($applicationData)) {
                $_SESSION['success'] = 'Lamaran Anda berhasil dikirim! Tim HR akan menghubungi Anda dalam 1-2 hari kerja.';
                
                // Send notification email (optional)
                $this->sendApplicationNotification($applicationData);
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.';
            }
            
            $this->redirect('/careers/' . $this->request->post('career_slug'));
        }
    }
    
    /**
     * Search careers
     */
    public function search()
    {
        $query = $this->request->get('q');
        $filters = [
            'employment_type' => $this->request->get('employment_type'),
            'experience_level' => $this->request->get('experience_level'),
            'department' => $this->request->get('department'),
            'limit' => 12
        ];
        
        $careers = $this->careerModel->search($query, $filters);
        $stats = $this->careerModel->getStats();
        
        // SEO Meta Tags
        $seoData = $this->seo->generateMetaTags([
            'title' => 'Pencarian Lowongan Kerja - Testweb Jersey',
            'description' => 'Cari lowongan kerja yang sesuai dengan keahlian dan minat Anda di Testweb Jersey.',
            'keywords' => 'pencarian kerja, lowongan kerja, testweb jersey, job search',
            'type' => 'website'
        ]);
        
        $this->render('frontend/careers/search', [
            'careers' => $careers,
            'stats' => $stats,
            'query' => $query,
            'filters' => $filters,
            'seoData' => $seoData,
            'pageTitle' => $seoData['title'],
            'pageDescription' => $seoData['description'],
            'pageKeywords' => $seoData['keywords']
        ]);
    }
    
    /**
     * Send application notification email
     */
    private function sendApplicationNotification($applicationData)
    {
        // This would typically send an email notification
        // For now, we'll just log it
        error_log("New job application: " . json_encode($applicationData));
    }
}