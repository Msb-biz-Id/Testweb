<?php

namespace App\Controllers\Admin;

use App\Models\Career;
use App\Models\JobApplication;
use App\Core\SEO;

/**
 * Admin Career Controller
 * Handles career management in admin panel
 */
class CareerController extends \App\Controllers\Controller
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
     * Display careers management page
     */
    public function index()
    {
        $filters = [
            'limit' => 20
        ];
        
        // Apply filters
        if ($this->request->get('status')) {
            $filters['status'] = $this->request->get('status');
        }
        
        if ($this->request->get('department')) {
            $filters['department'] = $this->request->get('department');
        }
        
        if ($this->request->get('search')) {
            $filters['search'] = $this->request->get('search');
        }
        
        $careers = $this->careerModel->getAll($filters);
        $stats = $this->careerModel->getStats();
        
        $this->render('admin/careers/index', [
            'careers' => $careers,
            'stats' => $stats,
            'filters' => $filters,
            'pageTitle' => 'Manage Careers',
            'breadcrumbs' => [
                ['title' => 'Careers']
            ]
        ]);
    }
    
    /**
     * Display create career form
     */
    public function create()
    {
        $this->render('admin/careers/create', [
            'pageTitle' => 'Create New Career',
            'breadcrumbs' => [
                ['title' => 'Careers', 'url' => '/admin/careers'],
                ['title' => 'Create']
            ]
        ]);
    }
    
    /**
     * Handle create career form submission
     */
    public function store()
    {
        if ($this->request->isPost()) {
            // Validate required fields
            $validation = $this->request->validate([
                'title' => 'required|min:5',
                'description' => 'required|min:50',
                'requirements' => 'required|min:20',
                'responsibilities' => 'required|min:20',
                'department' => 'required',
                'location' => 'required',
                'employment_type' => 'required',
                'experience_level' => 'required'
            ]);
            
            if ($validation !== true) {
                $_SESSION['error'] = 'Mohon lengkapi semua field yang diperlukan.';
                $this->redirect('/admin/careers/create');
                return;
            }
            
            // Generate slug
            $slug = $this->careerModel->generateSlug($this->request->post('title'));
            
            // Prepare career data
            $careerData = [
                'title' => $this->request->post('title'),
                'slug' => $slug,
                'description' => $this->request->post('description'),
                'requirements' => $this->request->post('requirements'),
                'responsibilities' => $this->request->post('responsibilities'),
                'benefits' => $this->request->post('benefits'),
                'employment_type' => $this->request->post('employment_type'),
                'experience_level' => $this->request->post('experience_level'),
                'department' => $this->request->post('department'),
                'location' => $this->request->post('location'),
                'salary_min' => $this->request->post('salary_min'),
                'salary_max' => $this->request->post('salary_max'),
                'currency' => $this->request->post('currency') ?: 'IDR',
                'application_deadline' => $this->request->post('application_deadline'),
                'start_date' => $this->request->post('start_date'),
                'status' => $this->request->post('status') ?: 'active',
                'featured' => $this->request->post('featured') ? 1 : 0,
                'meta_title' => $this->request->post('meta_title'),
                'meta_description' => $this->request->post('meta_description'),
                'meta_keywords' => $this->request->post('meta_keywords')
            ];
            
            // Create career
            if ($this->careerModel->create($careerData)) {
                $_SESSION['success'] = 'Career posting created successfully!';
                $this->redirect('/admin/careers');
            } else {
                $_SESSION['error'] = 'Failed to create career posting.';
                $this->redirect('/admin/careers/create');
            }
        }
    }
    
    /**
     * Display edit career form
     */
    public function edit($id)
    {
        $career = $this->careerModel->getById($id);
        
        if (!$career) {
            $_SESSION['error'] = 'Career not found.';
            $this->redirect('/admin/careers');
            return;
        }
        
        $this->render('admin/careers/edit', [
            'career' => $career,
            'pageTitle' => 'Edit Career: ' . $career['title'],
            'breadcrumbs' => [
                ['title' => 'Careers', 'url' => '/admin/careers'],
                ['title' => 'Edit']
            ]
        ]);
    }
    
    /**
     * Handle update career form submission
     */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $career = $this->careerModel->getById($id);
            
            if (!$career) {
                $_SESSION['error'] = 'Career not found.';
                $this->redirect('/admin/careers');
                return;
            }
            
            // Validate required fields
            $validation = $this->request->validate([
                'title' => 'required|min:5',
                'description' => 'required|min:50',
                'requirements' => 'required|min:20',
                'responsibilities' => 'required|min:20',
                'department' => 'required',
                'location' => 'required',
                'employment_type' => 'required',
                'experience_level' => 'required'
            ]);
            
            if ($validation !== true) {
                $_SESSION['error'] = 'Mohon lengkapi semua field yang diperlukan.';
                $this->redirect('/admin/careers/edit/' . $id);
                return;
            }
            
            // Generate slug if title changed
            $slug = $career['slug'];
            if ($this->request->post('title') !== $career['title']) {
                $slug = $this->careerModel->generateSlug($this->request->post('title'), $id);
            }
            
            // Prepare career data
            $careerData = [
                'title' => $this->request->post('title'),
                'slug' => $slug,
                'description' => $this->request->post('description'),
                'requirements' => $this->request->post('requirements'),
                'responsibilities' => $this->request->post('responsibilities'),
                'benefits' => $this->request->post('benefits'),
                'employment_type' => $this->request->post('employment_type'),
                'experience_level' => $this->request->post('experience_level'),
                'department' => $this->request->post('department'),
                'location' => $this->request->post('location'),
                'salary_min' => $this->request->post('salary_min'),
                'salary_max' => $this->request->post('salary_max'),
                'currency' => $this->request->post('currency') ?: 'IDR',
                'application_deadline' => $this->request->post('application_deadline'),
                'start_date' => $this->request->post('start_date'),
                'status' => $this->request->post('status') ?: 'active',
                'featured' => $this->request->post('featured') ? 1 : 0,
                'meta_title' => $this->request->post('meta_title'),
                'meta_description' => $this->request->post('meta_description'),
                'meta_keywords' => $this->request->post('meta_keywords')
            ];
            
            // Update career
            if ($this->careerModel->update($id, $careerData)) {
                $_SESSION['success'] = 'Career posting updated successfully!';
                $this->redirect('/admin/careers');
            } else {
                $_SESSION['error'] = 'Failed to update career posting.';
                $this->redirect('/admin/careers/edit/' . $id);
            }
        }
    }
    
    /**
     * Delete career
     */
    public function delete($id)
    {
        $career = $this->careerModel->getById($id);
        
        if (!$career) {
            $_SESSION['error'] = 'Career not found.';
            $this->redirect('/admin/careers');
            return;
        }
        
        if ($this->careerModel->delete($id)) {
            $_SESSION['success'] = 'Career posting deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete career posting.';
        }
        
        $this->redirect('/admin/careers');
    }
    
    /**
     * Display job applications
     */
    public function applications()
    {
        $filters = [
            'limit' => 20
        ];
        
        // Apply filters
        if ($this->request->get('career_id')) {
            $filters['career_id'] = $this->request->get('career_id');
        }
        
        if ($this->request->get('status')) {
            $filters['status'] = $this->request->get('status');
        }
        
        if ($this->request->get('search')) {
            $filters['search'] = $this->request->get('search');
        }
        
        $applications = $this->applicationModel->getAll($filters);
        $stats = $this->applicationModel->getStats();
        $careers = $this->careerModel->getAll(['status' => 'active']);
        
        $this->render('admin/careers/applications', [
            'applications' => $applications,
            'stats' => $stats,
            'careers' => $careers,
            'filters' => $filters,
            'pageTitle' => 'Job Applications',
            'breadcrumbs' => [
                ['title' => 'Careers', 'url' => '/admin/careers'],
                ['title' => 'Applications']
            ]
        ]);
    }
    
    /**
     * View single application
     */
    public function viewApplication($id)
    {
        $application = $this->applicationModel->getById($id);
        
        if (!$application) {
            $_SESSION['error'] = 'Application not found.';
            $this->redirect('/admin/careers/applications');
            return;
        }
        
        $this->render('admin/careers/view-application', [
            'application' => $application,
            'pageTitle' => 'Application: ' . $application['full_name'],
            'breadcrumbs' => [
                ['title' => 'Careers', 'url' => '/admin/careers'],
                ['title' => 'Applications', 'url' => '/admin/careers/applications'],
                ['title' => 'View']
            ]
        ]);
    }
    
    /**
     * Update application status
     */
    public function updateApplicationStatus($id)
    {
        if ($this->request->isPost()) {
            $application = $this->applicationModel->getById($id);
            
            if (!$application) {
                $_SESSION['error'] = 'Application not found.';
                $this->redirect('/admin/careers/applications');
                return;
            }
            
            $data = [
                'status' => $this->request->post('status'),
                'notes' => $this->request->post('notes')
            ];
            
            if ($this->applicationModel->update($id, $data)) {
                $_SESSION['success'] = 'Application status updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update application status.';
            }
            
            $this->redirect('/admin/careers/applications');
        }
    }
    
    /**
     * Delete application
     */
    public function deleteApplication($id)
    {
        $application = $this->applicationModel->getById($id);
        
        if (!$application) {
            $_SESSION['error'] = 'Application not found.';
            $this->redirect('/admin/careers/applications');
            return;
        }
        
        if ($this->applicationModel->delete($id)) {
            $_SESSION['success'] = 'Application deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete application.';
        }
        
        $this->redirect('/admin/careers/applications');
    }
    
    /**
     * Export applications to CSV
     */
    public function exportApplications()
    {
        $filters = [];
        
        if ($this->request->get('career_id')) {
            $filters['career_id'] = $this->request->get('career_id');
        }
        
        if ($this->request->get('status')) {
            $filters['status'] = $this->request->get('status');
        }
        
        $applications = $this->applicationModel->getAll($filters);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="job_applications_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'ID', 'Career Title', 'Full Name', 'Email', 'Phone', 
            'Status', 'Applied Date', 'Expected Salary', 'Portfolio URL', 'LinkedIn URL'
        ]);
        
        // CSV data
        foreach ($applications as $application) {
            fputcsv($output, [
                $application['id'],
                $application['career_title'],
                $application['full_name'],
                $application['email'],
                $application['phone'],
                $application['status'],
                $application['applied_at'],
                $application['expected_salary'],
                $application['portfolio_url'],
                $application['linkedin_url']
            ]);
        }
        
        fclose($output);
        exit;
    }
}