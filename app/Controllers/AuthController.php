<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login', [
            'pageTitle' => 'Connexion',
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('login'))->with('error', 'Identifiants incorrects. Veuillez réessayer.');
        }

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->authenticate(
            (string) $this->request->getPost('email'),
            (string) $this->request->getPost('password')
        );

        if ($employee === null) {
            return redirect()->to(site_url('login'))->with('error', 'Identifiants incorrects. Veuillez réessayer.');
        }

        $session = session();
        $session->remove([
            'user_id',
            'user_name',
            'user_email',
            'role',
            'departement_id',
            'is_logged_in',
        ]);
        $session->regenerate();

        $session->set([
            'user_id' => (int) $employee['id'],
            'user_name' => trim(((string) ($employee['prenom'] ?? '')) . ' ' . ((string) ($employee['nom'] ?? ''))),
            'user_email' => (string) ($employee['email'] ?? ''),
            'role' => (string) ($employee['role'] ?? 'employee'),
            'departement_id' => $employee['departement_id'] ?? null,
            'is_logged_in' => true,
        ]);

        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}