<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'departement_id',
        'date_embauche',
        'actif',
    ];
    protected $useTimestamps = false;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    public function authenticate(string $email, string $password): ?array
    {
        $employee = $this->where('email', $email)->first();
        if ($employee === null) {
            return null;
        }

        $storedPassword = (string) ($employee['password'] ?? '');
        if ($storedPassword === '') {
            return null;
        }

        $isValid = password_verify($password, $storedPassword);

        return $isValid ? $employee : null;
    }

    protected function hashPassword(array $data): array
    {
        if (! isset($data['data']['password'])) {
            return $data;
        }

        $password = (string) $data['data']['password'];
        if ($password === '' || password_get_info($password)['algo'] !== 0) {
            return $data;
        }

        $data['data']['password'] = password_hash($password, PASSWORD_DEFAULT);

        return $data;
    }
}