<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveBalanceModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['employee_id', 'type_conge_id', 'annee', 'jours_attribues', 'jours_pris'];
    protected $useTimestamps = false;
}