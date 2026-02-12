<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Login user - accepts both form submission and JSON request
     */
    public function login()
    {
        $json = $this->request->getJSON(true); // true = return as array
        $email = $this->request->getPost('email') ?? ($json['email'] ?? null);
        $password = $this->request->getPost('password') ?? ($json['password'] ?? null);

        if (!$email || !$password) {
            return $this->response->setStatusCode(400)
                ->setJSON(['message' => 'Email and password are required']);
        }

        // Authenticate user via Model
        $user = $this->userModel->authenticate($email, $password);

        if (!$user) {
            return $this->response->setStatusCode(401)
                ->setJSON(['message' => 'Invalid email or password']);
        }

        // Set session
        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'] ?? 'USER',
            'isLoggedIn' => true,
        ]);

        // Return JSON response for AJAX requests
        return $this->response->setJSON([
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
            ],
        ]);
    }

    /**
     * Register user - accepts both form submission and JSON request
     */
    public function register()
    {
        $json = $this->request->getJSON(true); // true = return as array
        $name = $this->request->getPost('name') ?? ($json['name'] ?? null);
        $email = $this->request->getPost('email') ?? ($json['email'] ?? null);
        $password = $this->request->getPost('password') ?? ($json['password'] ?? null);
        $passwordConfirm = $this->request->getPost('password_confirm') ?? ($json['password_confirm'] ?? null);

        // Validate input
        if (!$name || !$email || !$password || !$passwordConfirm) {
            return $this->response->setStatusCode(400)
                ->setJSON(['message' => 'All fields are required']);
        }

        if ($password !== $passwordConfirm) {
            return $this->response->setStatusCode(400)
                ->setJSON(['message' => 'Passwords do not match']);
        }

        if (strlen($password) < 8) {
            return $this->response->setStatusCode(400)
                ->setJSON(['message' => 'Password must be at least 8 characters']);
        }

        // Create new user via Model
        $newUser = $this->userModel->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'USER',
        ]);

        if (!$newUser) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'message' => 'Registration failed',
                    'errors' => $this->userModel->errors(),
                ]);
        }

        // Auto-login
        session()->set([
            'user_id' => $newUser['id'],
            'user_name' => $newUser['name'],
            'user_email' => $newUser['email'],
            'user_role' => $newUser['role'] ?? 'USER',
            'isLoggedIn' => true,
        ]);

        // Return JSON response for AJAX requests
        return $this->response->setJSON([
            'message' => 'Registration and login successful',
            'user' => [
                'id' => $newUser['id'],
                'email' => $newUser['email'],
                'name' => $newUser['name'],
            ],
        ]);
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session()->destroy();

        // Return JSON response for AJAX requests
        return $this->response->setJSON(['message' => 'Logout successful']);
    }
}
