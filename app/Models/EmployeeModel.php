<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'm_employees';
    protected $primaryKey       = 'm_employee_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_karyawan',
        'tanggal_lahir_karyawan',
        'alamat',
        'email',
        'valid_from',
        'valid_to',
        'create_by',
        'create_date',
        'update_by',
        'update_date'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'create_date';
    protected $updatedField  = 'update_date';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ["setCreateByAndUpdateBy"];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function findWithFamilies(int|string $id, EmployeeFamilyModel $model)
    {
        $employee = $this
            ->select(
                "m_employee_id AS id, 
            nama_karyawan AS nama, 
            tanggal_lahir_karyawan AS tanggal_lahir, 
            alamat, 
            email,
            valid_to"
            )
            ->find($id);

        if (!$employee) {
            return null;
        }

        $employee["keluarga"] = $model
            ->select("m_employee_family_id AS id, hubungan_keluarga AS hubungan, nama_anggota_keluarga AS nama, tanggal_lahir_anggota_keluarga as tanggal_lahir")
            ->where("m_employee_id", $id)
            ->findAll();

        return $employee;
    }

    protected function setCreateByAndUpdateBy(array $data): array
    {
        $data["data"]["create_by"] = 1;
        $data["data"]["update_by"] = 1;

        return $data;
    }
}
