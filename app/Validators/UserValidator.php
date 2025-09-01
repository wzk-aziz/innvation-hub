<?php

namespace App\Validators;

use App\Models\User;

/**
 * User Validator
 * Handles validation for user-related operations
 */
class UserValidator
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    /**
     * Validate user creation data
     */
    public function validate($data)
    {
        $errors = [];
        
        // First name validation
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required.';
        } elseif (strlen($data['first_name']) < 2) {
            $errors['first_name'] = 'First name must be at least 2 characters long.';
        } elseif (strlen($data['first_name']) > 100) {
            $errors['first_name'] = 'First name cannot be longer than 100 characters.';
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/', $data['first_name'])) {
            $errors['first_name'] = 'First name contains invalid characters.';
        }
        
        // Last name validation
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required.';
        } elseif (strlen($data['last_name']) < 2) {
            $errors['last_name'] = 'Last name must be at least 2 characters long.';
        } elseif (strlen($data['last_name']) > 100) {
            $errors['last_name'] = 'Last name cannot be longer than 100 characters.';
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/', $data['last_name'])) {
            $errors['last_name'] = 'Last name contains invalid characters.';
        }
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif (strlen($data['email']) > 180) {
            $errors['email'] = 'Email cannot be longer than 180 characters.';
        } elseif ($this->userModel->emailExists($data['email'])) {
            $errors['email'] = 'This email address is already in use.';
        }
        
        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $data['password'])) {
            $errors['password'] = 'Password must contain at least one lowercase letter, one uppercase letter, and one number.';
        }
        
        // Password confirmation validation
        if (empty($data['password_confirm'])) {
            $errors['password_confirm'] = 'Password confirmation is required.';
        } elseif ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'] = 'Password confirmation does not match.';
        }
        
        // Role validation
        if (empty($data['role'])) {
            $errors['role'] = 'Role is required.';
        } elseif (!in_array($data['role'], ['admin', 'salarie', 'evaluateur'])) {
            $errors['role'] = 'Invalid role selected.';
        }
        
        // Status validation
        if (isset($data['status']) && !in_array($data['status'], ['active', 'inactive'])) {
            $errors['status'] = 'Invalid status selected.';
        }
        
        return $errors;
    }
    
    /**
     * Validate user update data
     */
    public function validateUpdate($data, $userId)
    {
        $errors = [];
        
        // First name validation
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required.';
        } elseif (strlen($data['first_name']) < 2) {
            $errors['first_name'] = 'First name must be at least 2 characters long.';
        } elseif (strlen($data['first_name']) > 100) {
            $errors['first_name'] = 'First name cannot be longer than 100 characters.';
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/', $data['first_name'])) {
            $errors['first_name'] = 'First name contains invalid characters.';
        }
        
        // Last name validation
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required.';
        } elseif (strlen($data['last_name']) < 2) {
            $errors['last_name'] = 'Last name must be at least 2 characters long.';
        } elseif (strlen($data['last_name']) > 100) {
            $errors['last_name'] = 'Last name cannot be longer than 100 characters.';
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/', $data['last_name'])) {
            $errors['last_name'] = 'Last name contains invalid characters.';
        }
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif (strlen($data['email']) > 180) {
            $errors['email'] = 'Email cannot be longer than 180 characters.';
        } elseif ($this->userModel->emailExists($data['email'], $userId)) {
            $errors['email'] = 'This email address is already in use.';
        }
        
        // Password validation (optional for update)
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                $errors['password'] = 'Password must be at least 8 characters long.';
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $data['password'])) {
                $errors['password'] = 'Password must contain at least one lowercase letter, one uppercase letter, and one number.';
            }
            
            // Password confirmation validation
            if (empty($data['password_confirm'])) {
                $errors['password_confirm'] = 'Password confirmation is required when changing password.';
            } elseif ($data['password'] !== $data['password_confirm']) {
                $errors['password_confirm'] = 'Password confirmation does not match.';
            }
        }
        
        // Role validation
        if (empty($data['role'])) {
            $errors['role'] = 'Role is required.';
        } elseif (!in_array($data['role'], ['admin', 'salarie', 'evaluateur'])) {
            $errors['role'] = 'Invalid role selected.';
        }
        
        // Status validation
        if (isset($data['status']) && !in_array($data['status'], ['active', 'inactive'])) {
            $errors['status'] = 'Invalid status selected.';
        }
        
        return $errors;
    }
    
    /**
     * Validate login data
     */
    public function validateLogin($data)
    {
        $errors = [];
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required.';
        }
        
        return $errors;
    }
}
