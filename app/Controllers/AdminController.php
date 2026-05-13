<?php

namespace App\Controllers;

use App\Models\DepartmentModel;
use App\Models\EmployeeModel;
use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $employeeModel = new EmployeeModel();
        $departmentModel = new DepartmentModel();
        $requestModel = new LeaveRequestModel();

        return view('admin/dashboard', [
            'pageTitle' => 'Administration',
            'activeMenu' => 'admin-dashboard',
            'employeeCount' => $employeeModel->countAllResults(),
            'departmentCount' => $departmentModel->countAllResults(),
            'requestCount' => $requestModel->countAllResults(),
            'pendingCount' => (new LeaveRequestModel())->where('statut', 'en_attente')->countAllResults(),
            'approvedCount' => (new LeaveRequestModel())->where('statut', 'approuve')->countAllResults(),
        ]);
    }

    public function employees()
    {
        $employeeModel = new EmployeeModel();
        $departmentModel = new DepartmentModel();

        return view('admin/employes', [
            'pageTitle' => 'Gestion des employés',
            'activeMenu' => 'admin-employees',
            'employees' => $employeeModel
                ->select('employees.*, departements.nom AS departement_nom')
                ->join('departements', 'departements.id = employees.departement_id', 'left')
                ->orderBy('employees.id', 'ASC')
                ->findAll(),
            'departments' => $departmentModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function departements()
    {
        $departmentModel = new DepartmentModel();

        return view('admin/departements', [
            'pageTitle' => 'Départements',
            'activeMenu' => 'admin-departments',
            'departments' => $departmentModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function typesConge()
    {
        $typeModel = new LeaveTypeModel();

        return view('admin/types_conge', [
            'pageTitle' => 'Types de congé',
            'activeMenu' => 'admin-types',
            'types' => $typeModel->orderBy('libelle', 'ASC')->findAll(),
        ]);
    }

    public function storeEmployee()
    {
        $rules = [
            'nom' => 'required|min_length[2]',
            'prenom' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('admin/employes'))->with('error', 'Veuillez corriger le formulaire employé.');
        }

        $employeeModel = new EmployeeModel();
        $employeeModel->insert([
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => trim((string) $this->request->getPost('password')),
            'role' => (string) ($this->request->getPost('role') ?: 'employee'),
            'departement_id' => (int) ($this->request->getPost('departement_id') ?: 0) ?: null,
            'date_embauche' => (string) ($this->request->getPost('date_embauche') ?: date('Y-m-d')),
            'actif' => 1,
        ]);

        return redirect()->to(site_url('admin/employes'))->with('success', 'Employé ajouté avec succès.');
    }

    public function storeDepartment()
    {
        $rules = [
            'nom' => 'required|min_length[2]',
            'description' => 'permit_empty|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('admin/departements'))->with('error', 'Veuillez corriger le formulaire département.');
        }

        (new DepartmentModel())->insert([
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        return redirect()->to(site_url('admin/departements'))->with('success', 'Département ajouté avec succès.');
    }

    public function storeType()
    {
        $rules = [
            'libelle' => 'required|min_length[2]',
            'jours_annuels' => 'required|is_natural',
            'deductible' => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('admin/types-conge'))->with('error', 'Veuillez corriger le formulaire type.');
        }

        (new LeaveTypeModel())->insert([
            'libelle' => trim((string) $this->request->getPost('libelle')),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible' => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->to(site_url('admin/types-conge'))->with('success', 'Type de congé ajouté avec succès.');
    }
}