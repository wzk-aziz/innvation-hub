<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Models\User;
use App\Validators\UserValidator;

/**
 * Admin User Controller
 * Handles user management for administrators
 */
class UserController extends Controller
{
    private $userModel;
    private $validator;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
        $this->userModel = new User();
        $this->validator = new UserValidator();
    }
    
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $this->redirect('/');
    }
    
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;

        try {
            $users = $this->userModel->getAllPaginated($page, $perPage, $search, $role);
            $totalUsers = $this->userModel->countAll($search, $role);
            $totalPages = ceil($totalUsers / $perPage);
        } catch (Exception $e) {
            // Fallback to simple data if there's an error
            $users = [];
            $totalUsers = 0;
            $totalPages = 1;
        }

        $data = [
            'pageTitle' => 'Gestion des Utilisateurs',
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers,
            'roles' => ['Admin', 'Salarie', 'Evaluateur']
        ];

        $this->render('admin/users/index', $data, 'admin');
    }
    
    /**
     * Show create user form
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Create User',
            'roles' => ['admin', 'salarie', 'evaluateur'],
            'statuses' => ['active', 'inactive']
        ];
        
        $this->render('admin/users/create', $data);
    }
    
    /**
     * Store new user
     */
    public function store()
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $request = new Request();
        $data = $request->only(['first_name', 'last_name', 'email', 'password', 'password_confirm', 'role', 'status']);
        
        // Validate input
        $errors = $this->validator->validate($data);
        
        if (!empty($errors)) {
            $this->setFlashMessage('Please correct the errors below.', 'error');
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $request->except(['password', 'password_confirm']);
            $this->redirect('/admin/users/create');
            return;
        }
        
        // Hash password
        $data['password_hash'] = Auth::hashPassword($data['password']);
        unset($data['password'], $data['password_confirm']);
        
        // Create user
        $userId = $this->userModel->create($data);
        
        if ($userId) {
            $this->setFlashMessage('User created successfully.', 'success');
            $this->redirect('/admin/users');
        } else {
            $this->setFlashMessage('Failed to create user. Please try again.', 'error');
            $this->redirect('/admin/users/create');
        }
    }
    
    /**
     * Show user details
     */
    public function show($id)
    {
        // Ensure ID is an integer
        $id = (int) $id;
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $this->setFlashMessage('User not found.', 'error');
            $this->redirect('/admin/users');
            return;
        }
        
        $data = [
            'user' => $user,
            'pageTitle' => 'User Details'
        ];
        
        $this->render('admin/users/show', $data);
    }
    
    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $this->setFlashMessage('User not found.', 'error');
            $this->redirect('/admin/users');
            return;
        }
        
        $data = [
            'user' => $user,
            'roles' => ['admin', 'salarie', 'evaluateur'],
            'statuses' => ['active', 'inactive'],
            'pageTitle' => 'Edit User'
        ];
        
        $this->render('admin/users/edit', $data);
    }
    
    /**
     * Update user
     */
    public function update($id)
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $this->setFlashMessage('User not found.', 'error');
            $this->redirect('/admin/users');
            return;
        }
        
        $request = new Request();
        $data = $request->only(['first_name', 'last_name', 'email', 'password', 'password_confirm', 'role', 'status']);
        
        // Validate input
        $errors = $this->validator->validateUpdate($data, $id);
        
        if (!empty($errors)) {
            $this->setFlashMessage('Please correct the errors below.', 'error');
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $request->except(['password', 'password_confirm']);
            $this->redirect("/admin/users/{$id}/edit");
            return;
        }
        
        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password_hash'] = Auth::hashPassword($data['password']);
        }
        unset($data['password'], $data['password_confirm']);
        
        // Update user
        if ($this->userModel->update($id, $data)) {
            $this->setFlashMessage('User updated successfully.', 'success');
            $this->redirect('/admin/users');
        } else {
            $this->setFlashMessage('Failed to update user. Please try again.', 'error');
            $this->redirect("/admin/users/{$id}/edit");
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id)
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $this->setFlashMessage('User not found.', 'error');
            $this->redirect('/admin/users');
            return;
        }
        
        // Prevent deleting current user
        if ($user['id'] == Auth::id()) {
            $this->setFlashMessage('You cannot delete your own account.', 'error');
            $this->redirect('/admin/users');
            return;
        }
        
        if ($this->userModel->delete($id)) {
            $this->setFlashMessage('User deleted successfully.', 'success');
        } else {
            $this->setFlashMessage('Failed to delete user. Please try again.', 'error');
        }
        
        $this->redirect('/admin/users');
    }
}
