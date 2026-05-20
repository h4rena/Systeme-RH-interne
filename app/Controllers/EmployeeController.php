<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\LeaveBalanceModel;
use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;
use DateTimeImmutable;

class EmployeeController extends BaseController
{
    public function dashboard()
    {
        $employeeId = (int) session()->get('user_id');
        $currentYear = (int) ($this->request->getGet('year') ?: date('Y'));

        $employee = (new EmployeeModel())
            ->select('employees.*, departements.nom AS departement_nom')
            ->join('departements', 'departements.id = employees.departement_id', 'left')
            ->find($employeeId);

        $balances = (new LeaveBalanceModel())
            ->select('soldes.*, types_conge.libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id', 'left')
            ->where('employee_id', $employeeId)
            ->where('annee', $currentYear)
            ->orderBy('types_conge.libelle', 'ASC')
            ->findAll();

        $allRequests = (new LeaveRequestModel())
            ->select('conges.*, types_conge.libelle AS type_label')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
            ->where('conges.employee_id', $employeeId)
            ->where("strftime('%Y', conges.date_debut)", (string) $currentYear)
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();

        $requests = array_slice($allRequests, 0, 5);

        $leaveTypeCounts = [];
        $calendarEvents = [];
        $statusPalette = [
            'approuve' => '#3c7a53',
            'en_attente' => '#d39f33',
            'refuse' => '#b13b31',
            'annulee' => '#7a8078',
        ];

        foreach ($allRequests as $request) {
            $typeLabel = (string) ($request['type_label'] ?? 'Autre');
            $leaveTypeCounts[$typeLabel] = ($leaveTypeCounts[$typeLabel] ?? 0) + 1;

            $status = (string) ($request['statut'] ?? 'en_attente');
            $calendarEvents[] = [
                'title' => $typeLabel,
                'start' => (string) ($request['date_debut'] ?? ''),
                // FullCalendar expects an exclusive end date for all-day events.
                'end' => date('Y-m-d', strtotime((string) ($request['date_fin'] ?? 'now') . ' +1 day')),
                'allDay' => true,
                'backgroundColor' => $statusPalette[$status] ?? '#3c7a53',
                'borderColor' => $statusPalette[$status] ?? '#3c7a53',
            ];
        }

        ksort($leaveTypeCounts);

        return view('employee/dashboard', [
            'pageTitle' => 'Tableau de bord employé',
            'activeMenu' => 'employee-dashboard',
            'employee' => $employee,
            'balances' => $balances,
            'requests' => $requests,
            'requestCountYear' => count($allRequests),
            'leaveTypeChart' => [
                'labels' => array_keys($leaveTypeCounts),
                'data' => array_values($leaveTypeCounts),
            ],
            'calendarEvents' => $calendarEvents,
            'currentYear' => $currentYear,
        ]);
    }

    public function index()
    {
        $employeeId = (int) session()->get('user_id');
        $currentYear = (int) ($this->request->getGet('year') ?: date('Y'));

        $requests = (new LeaveRequestModel())
            ->select('conges.*, types_conge.libelle AS type_label')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
            ->where('conges.employee_id', $employeeId)
            ->where("strftime('%Y', conges.date_debut)", (string) $currentYear)
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();

        return view('employee/index', [
            'pageTitle' => 'Mes demandes de congé',
            'activeMenu' => 'employee-requests',
            'requests' => $requests,
            'currentYear' => $currentYear,
        ]);
    }

    public function create()
    {
        $employeeId = (int) session()->get('user_id');
        $currentYear = (int) ($this->request->getGet('year') ?: date('Y'));

        $types = (new LeaveTypeModel())->orderBy('libelle', 'ASC')->findAll();
        $balances = (new LeaveBalanceModel())
            ->select('soldes.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id', 'left')
            ->where('employee_id', $employeeId)
            ->where('annee', $currentYear)
            ->findAll();

        return view('employee/create', [
            'pageTitle' => 'Nouvelle demande',
            'activeMenu' => 'employee-create',
            'types' => $types,
            'balances' => $balances,
            'currentYear' => $currentYear,
        ]);
    }

    public function store()
    {
        $rules = [
            'type_conge_id' => 'required|is_natural_no_zero',
            'date_debut' => 'required|valid_date[Y-m-d]',
            'date_fin' => 'required|valid_date[Y-m-d]',
            'motif' => 'required|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('employee/conges/create'))->with('error', 'Veuillez corriger le formulaire.');
        }

        $dateDebut = new DateTimeImmutable((string) $this->request->getPost('date_debut'));
        $dateFin = new DateTimeImmutable((string) $this->request->getPost('date_fin'));

        if ($dateFin < $dateDebut) {
            return redirect()->to(site_url('employee/conges/create'))->with('error', 'La date de fin doit être postérieure à la date de début.');
        }

        $leaveTypeModel = new LeaveTypeModel();
        $leaveRequestModel = new LeaveRequestModel();
        $leaveBalanceModel = new LeaveBalanceModel();
        $employeeId = (int) session()->get('user_id');
        $typeId = (int) $this->request->getPost('type_conge_id');
        $days = $dateDebut->diff($dateFin)->days + 1;

        $leaveType = $leaveTypeModel->find($typeId);
        if ($leaveType === null) {
            return redirect()->to(site_url('employee/conges/create'))->with('error', 'Le type de congé est invalide.');
        }

        if ((int) $leaveType['deductible'] === 1) {
            $balance = $leaveBalanceModel
                ->where('employee_id', $employeeId)
                ->where('type_conge_id', $typeId)
                ->where('annee', (int) $dateDebut->format('Y'))
                ->first();

            if ($balance !== null) {
                $available = (int) $balance['jours_attribues'] - (int) $balance['jours_pris'];
                if ($days > $available) {
                    return redirect()->to(site_url('employee/conges/create'))->with('error', 'Votre solde est insuffisant pour cette demande.');
                }
            }
        }

        $leaveRequestModel->insert([
            'employee_id' => $employeeId,
            'type_conge_id' => $typeId,
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => $dateFin->format('Y-m-d'),
            'nb_jours' => $days,
            'motif' => trim((string) $this->request->getPost('motif')),
            'statut' => 'en_attente',
            'commentaire_rh' => null,
            'traite_par' => null,
        ]);

        return redirect()->to(site_url('employee/conges'))->with('success', 'Votre demande de congé a bien été soumise.');
    }
}