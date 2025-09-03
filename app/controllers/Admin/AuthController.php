<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User;

/**
 * Admin Auth Controller
 * Handles admin authentication
 */
class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        // Redirect if already logged in
        if ($this->isAuthenticated()) {
            $this->redirect('/admin/dashboard');
            return;
        }
        
        $data = [
            'pageTitle' => 'Login Admin - Testweb Jersey'
        ];
        
        $this->render('admin/auth/login', $data);
    }

    /**
     * Handle login
     */
    public function login()
    {
        // Redirect if already logged in
        if ($this->isAuthenticated()) {
            $this->redirect('/admin/dashboard');
            return;
        }
        
        // Validate input
        $errors = $this->request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->setFlashMessage('Username dan password harus diisi', 'error');
            $this->redirect('/admin/login');
            return;
        }
        
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        
        // Authenticate user
        $user = $this->userModel->authenticate($username, $password);
        
        if ($user && $user['is_active']) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['full_name'];
            
            $this->setFlashMessage('Login berhasil! Selamat datang, ' . $user['full_name'], 'success');
            $this->redirect('/admin/dashboard');
        } else {
            $this->setFlashMessage('Username atau password salah', 'error');
            $this->redirect('/admin/login');
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        // Destroy session
        session_destroy();
        
        $this->redirect('/admin/login');
    }
}