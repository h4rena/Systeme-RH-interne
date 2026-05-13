<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (! session()->get('is_logged_in')) {
            return redirect()->to(site_url('login'));
        }

        return redirect()->to($this->dashboardFor((string) session()->get('role')));
    }

    private function dashboardFor(string $role): string
    {
        return match ($role) {
            'admin' => site_url('admin/dashboard'),
            'rh' => site_url('rh/dashboard'),
            default => site_url('employee/dashboard'),
        };
    }
}
