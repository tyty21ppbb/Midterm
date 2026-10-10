<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    /**
     * Display the login form.
     */
    public function login()
    {
        // Redirect an already authenticated staff member.
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/login', [
            'title' => 'Staff Login',
        ]);
    }

    /**
     * Process the submitted login form.
     */
    public function authenticate(): RedirectResponse
    {
        $validationRules = [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|max_length[50]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
            ],
        ];

        if (! $this->validate($validationRules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();

        $user = $userModel
            ->where('username', $username)
            ->where('is_active', 1)
            ->first();

        if (
            $user === null
            || ! password_verify($password, $user['password'])
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        // Prevent session-fixation attacks after successful authentication.
        session()->regenerate(true);

        session()->set([
            'user_id'      => $user['id'],
            'username'     => $user['username'],
            'full_name'    => $user['full_name'],
            'avatar'       => $user['avatar'],
            'is_logged_in' => true,
        ]);

        return redirect()
            ->to(site_url('dashboard'))
            ->with(
                'success',
                'Welcome, ' . $user['full_name'] . '!'
            );
    }

    /**
     * End the authenticated staff session.
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()
            ->to(site_url('login'))
            ->with('success', 'You have been logged out.');
    }
}