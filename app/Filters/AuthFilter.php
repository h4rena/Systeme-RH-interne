<?php

namespace App\Filters;

use App\Models\EmployeeModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Vous devez vous connecter pour accéder à cette page.');
        }

        $userId = (int) $session->get('user_id');
        if ($userId <= 0) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Session invalide. Veuillez vous reconnecter.');
        }

        $employee = (new EmployeeModel())->find($userId);
        if ($employee === null || (int) ($employee['actif'] ?? 0) !== 1) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Compte introuvable ou inactif. Veuillez vous reconnecter.');
        }

        $session->set([
            'user_id' => (int) $employee['id'],
            'user_name' => trim(((string) ($employee['prenom'] ?? '')) . ' ' . ((string) ($employee['nom'] ?? ''))),
            'user_email' => (string) ($employee['email'] ?? ''),
            'role' => (string) ($employee['role'] ?? 'employee'),
            'departement_id' => $employee['departement_id'] ?? null,
            'is_logged_in' => true,
        ]);

        $allowedRoles = array_values(array_filter((array) $arguments));
        if ($allowedRoles !== [] && ! in_array((string) $session->get('role'), $allowedRoles, true)) {
            return redirect()->to('/')->with('error', 'Accès non autorisé.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}