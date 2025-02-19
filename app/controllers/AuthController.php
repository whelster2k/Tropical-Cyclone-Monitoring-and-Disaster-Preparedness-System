<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Middleware\Auth;

class AuthController extends Controller {
    /**
     * Show login form
     *
     * @return void
     */
    public function loginAction() {
        if (Auth::userId()) {
            $this->redirect('/');
        }

        $this->render('auth/login');
    }

    /**
     * Process login form
     *
     * @return void
     */
    public function loginPostAction() {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        $email = $this->post('email');
        $password = $this->post('password');
        $remember = (bool)$this->post('remember');

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Please fill in all fields';
            $this->redirect('/login');
        }

        $user = User::authenticate($email, $password);

        if (!$user) {
            $_SESSION['error'] = 'Invalid credentials';
            $this->redirect('/login');
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        // Set remember me cookie if requested
        if ($remember) {
            $token = Auth::generateToken($user);
            setcookie('remember_token', $token, time() + 60*60*24*30, '/', '', true, true);
        }

        $this->redirect('/');
    }

    /**
     * Show registration form
     *
     * @return void
     */
    public function registerAction() {
        if (Auth::userId()) {
            $this->redirect('/');
        }

        $this->render('auth/register');
    }

    /**
     * Process registration form
     *
     * @return void
     */
    public function registerPostAction() {
        if (!$this->isPost()) {
            $this->redirect('/register');
        }

        $data = [
            'username' => $this->post('username'),
            'email' => $this->post('email'),
            'password' => $this->post('password'),
            'password_confirm' => $this->post('password_confirm'),
            'first_name' => $this->post('first_name'),
            'last_name' => $this->post('last_name')
        ];

        // Validate required fields
        foreach ($data as $field => $value) {
            if (empty($value)) {
                $_SESSION['error'] = 'Please fill in all fields';
                $_SESSION['old'] = $data;
                $this->redirect('/register');
            }
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format';
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }

        // Check if email exists
        if (User::findByEmail($data['email'])) {
            $_SESSION['error'] = 'Email already registered';
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }

        // Validate password
        if (strlen($data['password']) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters long';
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }

        if ($data['password'] !== $data['password_confirm']) {
            $_SESSION['error'] = 'Passwords do not match';
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }

        // Remove password confirmation
        unset($data['password_confirm']);

        try {
            $user_id = User::register($data);
            $user = User::find($user_id);

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['success'] = 'Registration successful';

            $this->redirect('/');

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Registration failed';
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }
    }

    /**
     * Process logout
     *
     * @return void
     */
    public function logoutAction() {
        // Clear session
        session_destroy();
        
        // Clear remember me cookie
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        
        $this->redirect('/login');
    }

    /**
     * Show forgot password form
     *
     * @return void
     */
    public function forgotPasswordAction() {
        if (Auth::userId()) {
            $this->redirect('/');
        }

        $this->render('auth/forgot-password');
    }

    /**
     * Process forgot password form
     *
     * @return void
     */
    public function forgotPasswordPostAction() {
        if (!$this->isPost()) {
            $this->redirect('/forgot-password');
        }

        $email = $this->post('email');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please enter a valid email address';
            $this->redirect('/forgot-password');
        }

        $user = User::findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'No account found with that email address';
            $this->redirect('/forgot-password');
        }

        // Generate password reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        try {
            User::updateProfile($user['id'], [
                'reset_token' => $token,
                'reset_expires' => $expires
            ]);

            // Send password reset email
            $reset_url = getenv('APP_URL') . "/reset-password?token={$token}";
            // TODO: Implement email sending

            $_SESSION['success'] = 'Password reset instructions have been sent to your email';
            $this->redirect('/login');

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to process password reset request';
            $this->redirect('/forgot-password');
        }
    }

    /**
     * Show reset password form
     *
     * @return void
     */
    public function resetPasswordAction() {
        if (Auth::userId()) {
            $this->redirect('/');
        }

        $token = $this->get('token');
        if (empty($token)) {
            $this->redirect('/login');
        }

        $this->render('auth/reset-password', ['token' => $token]);
    }

    /**
     * Process reset password form
     *
     * @return void
     */
    public function resetPasswordPostAction() {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        $token = $this->post('token');
        $password = $this->post('password');
        $password_confirm = $this->post('password_confirm');

        if (empty($token) || empty($password) || empty($password_confirm)) {
            $_SESSION['error'] = 'Please fill in all fields';
            $this->redirect("/reset-password?token={$token}");
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters long';
            $this->redirect("/reset-password?token={$token}");
        }

        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'Passwords do not match';
            $this->redirect("/reset-password?token={$token}");
        }

        // Find user by reset token
        $user = User::where(['reset_token' => $token])[0] ?? null;

        if (!$user || strtotime($user['reset_expires']) < time()) {
            $_SESSION['error'] = 'Invalid or expired password reset token';
            $this->redirect('/forgot-password');
        }

        try {
            User::updateProfile($user['id'], [
                'password' => $password,
                'reset_token' => null,
                'reset_expires' => null
            ]);

            $_SESSION['success'] = 'Password has been reset successfully';
            $this->redirect('/login');

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to reset password';
            $this->redirect("/reset-password?token={$token}");
        }
    }
} 