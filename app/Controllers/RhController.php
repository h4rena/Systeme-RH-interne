<?php

namespace App\Controllers;

use App\Models\LeaveBalanceModel;
use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;
use DateTimeImmutable;

class RhController extends BaseController
{
    public function dashboard()
    {
        $currentYear = (int) ($this->request->getGet('year') ?: date('Y'));
        $requests = $this->buildRequestsQuery($currentYear)->where('conges.statut', 'en_attente')->findAll();

        return view('rh/dashboard', [
            'pageTitle' => 'Validation des congés',
            'activeMenu' => 'rh-dashboard',
            'requests' => $requests,
            'currentYear' => $currentYear,
            'pendingCount' => count($requests),
            'approvedCount' => $this->buildRequestsQuery($currentYear)->where('conges.statut', 'approuve')->countAllResults(),
            'refusedCount' => $this->buildRequestsQuery($currentYear)->where('conges.statut', 'refuse')->countAllResults(),
        ]);
    }

    public function index()
    {
        $currentYear = (int) ($this->request->getGet('year') ?: date('Y'));
        $requests = $this->buildRequestsQuery($currentYear)->findAll();

        return view('rh/index', [
            'pageTitle' => 'Toutes les demandes',
            'activeMenu' => 'rh-requests',
            'requests' => $requests,
            'currentYear' => $currentYear,
        ]);
    }

    public function approve(int $id)
    {
        $result = $this->processDecision($id, 'approuve');
        if ($result !== null) {
            return $result;
        }

        return redirect()->to(site_url('rh/dashboard'))->with('success', 'Demande approuvée.');
    }

    public function refuse(int $id)
    {
        $result = $this->processDecision($id, 'refuse');
        if ($result !== null) {
            return $result;
        }

        return redirect()->to(site_url('rh/dashboard'))->with('success', 'Demande refusée.');
    }

    private function buildRequestsQuery(?int $year = null)
    {
        $query = (new LeaveRequestModel())
            ->select('conges.*, types_conge.libelle AS type_label, employees.nom AS employee_nom, employees.prenom AS employee_prenom, departements.nom AS departement_nom')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
            ->join('employees', 'employees.id = conges.employee_id', 'left')
            ->join('departements', 'departements.id = employees.departement_id', 'left');

        if ($year !== null) {
            $query->where("strftime('%Y', conges.date_debut)", (string) $year);
        }

        return $query;
    }

    private function processDecision(int $id, string $status)
    {
        $leaveRequestModel = new LeaveRequestModel();
        $leaveTypeModel = new LeaveTypeModel();
        $leaveBalanceModel = new LeaveBalanceModel();

        $leaveRequest = $leaveRequestModel->find($id);
        if ($leaveRequest === null) {
            return redirect()->to(site_url('rh/dashboard'))->with('error', 'Demande introuvable.');
        }

        if ($status === 'approuve') {
            $leaveType = $leaveTypeModel->find((int) $leaveRequest['type_conge_id']);
            if ($leaveType !== null && (int) $leaveType['deductible'] === 1) {
                $year = (int) (new DateTimeImmutable((string) $leaveRequest['date_debut']))->format('Y');
                $balance = $leaveBalanceModel
                    ->where('employee_id', (int) $leaveRequest['employee_id'])
                    ->where('type_conge_id', (int) $leaveRequest['type_conge_id'])
                    ->where('annee', $year)
                    ->first();

                if ($balance !== null) {
                    $available = (int) $balance['jours_attribues'] - (int) $balance['jours_pris'];
                    if ((int) $leaveRequest['nb_jours'] > $available) {
                        return redirect()->to(site_url('rh/dashboard'))->with('error', 'Solde insuffisant pour valider cette demande.');
                    }

                    $leaveBalanceModel->update((int) $balance['id'], [
                        'jours_pris' => (int) $balance['jours_pris'] + (int) $leaveRequest['nb_jours'],
                    ]);
                }
            }
        }

        $leaveRequestModel->update($id, [
            'statut' => $status,
            'commentaire_rh' => $status === 'approuve' ? 'Validé par le responsable RH.' : 'Refusé par le responsable RH.',
            'traite_par' => (int) session()->get('user_id'),
        ]);

        return null;
    }
}