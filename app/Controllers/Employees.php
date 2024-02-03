<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeFamilyModel;
use App\Models\EmployeeModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\I18n\Time;

class Employees extends BaseController
{
    private EmployeeModel $employeeModel;
    private EmployeeFamilyModel $employeeFamilyModel;

    public function __construct()
    {
        helper(["json", "session"]);

        $this->employeeModel = new EmployeeModel();
        $this->employeeFamilyModel = new EmployeeFamilyModel();
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(): string
    {
        $employees = $this->employeeModel
            ->select("m_employee_id AS id, nama_karyawan AS nama, alamat, email")
            ->findAll();

        return view("employees/index", compact("employees"));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function new(): string
    {
        $familyRelationships = $this->getFamilyRelationships();

        return view("employees/create", compact("familyRelationships"));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function create()
    {
        // dd($this->request->getPost());
        $familiesCount = (int) $this->request->getPost("jumlah_keluarga");

        $rules = [...$this->getBaseRules(), ...$this->getFamiliyRules($familiesCount)];

        $isValid = $this->validate($rules);

        if (!$isValid) {
            return redirect()->back()->withInput();
        }

        $validated = $this->validator->getValidated();

        $db = \Config\Database::connect();

        try {
            $db->transException(true)->transStart();

            $this->employeeModel->insert($this->getEmployeeInput($validated));

            $this->employeeFamilyModel->insertBatch($this->getFamiliesInput($familiesCount, $validated, $this->employeeModel->getInsertID()));

            $db->transComplete();
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with("notif", [
                    "type" => "error",
                    "message" => $e->getMessage(),
                ]);
        }

        return redirect()
            ->to("/employees")
            ->with("notif", [
                "type" => "success",
                "message" => "Data karyawan berhasil ditambah."
            ]);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(string $id)
    {
        helper(["date"]);
        $employee = $this->employeeModel->findWithFamilies($id, $this->employeeFamilyModel);

        $familyRelationships = array_column($this->getFamilyRelationships(), "label", "value");

        return view("employees/show", compact("employee", "familyRelationships"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(string $id): string
    {
        $familyRelationships = $this->getFamilyRelationships();

        $employee = $this->employeeModel->findWithFamilies($id, $this->employeeFamilyModel);

        return view("employees/edit", compact("employee", "familyRelationships"));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(string $id)
    {
        $employee = $this->employeeModel->find($id);
        if (!$employee) {
            return redirect()->back()->with("notif", [
                "type" => "error",
                "message" => "Data karyawan tidak ditemukan"
            ]);
        }

        $familiesCount = (int) $this->request->getPost("jumlah_keluarga");

        $rules = [...$this->getBaseRules(), ...$this->getFamiliyRules($familiesCount)];

        $isValid = $this->validate($rules);

        if (!$isValid) {
            return redirect()->back()->withInput();
        }

        $validated = $this->validator->getValidated();

        $db = \Config\Database::connect();

        try {
            $db->transException(true)->transStart();

            $this->employeeModel->update($id, $this->getEmployeeInput($validated));

            $deleteIDs = $this->request->getPost("delete_id");

            if ($deleteIDs) {
                $this->employeeFamilyModel->delete($deleteIDs);
            }

            $db->table("m_employee_families")->upsertBatch($this->getFamiliesInput($familiesCount, $validated, $id, true));

            $db->transComplete();
        } catch (DatabaseException $e) {
            return redirect()
                ->back()
                ->with("notif", [
                    "type" => "error",
                    "message" => $e->getMessage(),
                ]);
        }

        return redirect()
            ->to("/employees/{$id}")
            ->with("notif", [
                "type" => "success",
                "message" => "Data karyawan berhasil diperbarui."
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function delete(string $id)
    {
        $this->employeeModel->delete($id);

        return redirect()
            ->to("/employees")
            ->with("notif", [
                "type" => "success",
                "message" => "Data karyawan berhasil dihapus."
            ]);
    }


    private function getFamilyRelationships(): array
    {
        return [
            [
                "value" => "ayah",
                "label" => "Ayah"
            ],
            [
                "value" => "ibu",
                "label" => "Ibu"
            ],
            [
                "value" => "suami",
                "label" => "Suami"
            ],
            [
                "value" => "istri",
                "label" => "Istri"
            ],
            [
                "value" => "anak_ke_1",
                "label" => "Anak Ke-1"
            ],
            [
                "value" => "anak_ke_2",
                "label" => "Anak Ke-2"
            ],
            [
                "value" => "anak_ke_3",
                "label" => "Anak Ke-3"
            ],
            [
                "value" => "anak_ke_4",
                "label" => "Anak Ke-4"
            ],
            [
                "value" => "anak_ke_5",
                "label" => "Anak Ke-5"
            ]
        ];
    }

    private function getBaseRules(): array
    {
        return [
            'nama_karyawan' => [
                'label' => 'nama karyawan',
                'rules' => ['required']
            ],
            'tanggal_lahir' => [
                'label' => 'tanggal lahir',
                'rules' => ['required', 'valid_date[Y-m-d]']
            ],
            'alamat' => [
                'rules' => ['required']
            ],
            'email' => [
                'label' => 'alamat email',
                'rules' => ['required', 'valid_email']
            ],
            'valid_to' => [
                'label' => 'valid hingga',
                'rules' => ['permit_empty', 'valid_date[Y-m-d]']
            ]
        ];
    }

    private function getFamiliyRules(int $jumlah): array
    {
        $rules = [];
        for ($i = 0; $i < $jumlah; $i++) {
            if ($i == 0) {
                $rules = $this->getFirstFamiliyRules();
            } else {
                $rules = [...$rules, ...$this->getFamilyRules($i)];
            }
        }

        return $rules;
    }

    private function getFirstFamiliyRules(): array
    {
        return [
            "hubungan_keluarga.0" => [
                "label" => "hubungan keluarga",
                "rules" => ["required"],
            ],
            "nama_keluarga.0" => [
                "label" => "nama keluarga",
                "rules" => ["required"],
            ],
            "tanggal_lahir_keluarga.0" => [
                "label" => "tanggal lahir keluarga",
                "rules" => ["required"],
            ]
        ];
    }

    private function getFamilyRules(int $index): array
    {
        return [
            "hubungan_keluarga.{$index}" => [
                "label" => "Hubungan keluarga",
                "rules" => ["required_with[nama_keluarga.{$index},tanggal_lahir_keluarga.{$index}]"],
                'errors' => [
                    'required_with' => "Hubungan keluarga harus diisi jika nama keluarga atau tanggal lahir keluarga terisi."
                ]
            ],
            "nama_keluarga.{$index}" => [
                "label" => "Nama keluarga",
                "rules" => ["required_with[hubungan_keluarga.{$index},tanggal_lahir_keluarga.{$index}]"],
                'errors' => [
                    'required_with' => "Nama keluarga harus diisi jika hubungan keluarga atau tanggal lahir keluarga terisi."
                ]
            ],
            "tanggal_lahir_keluarga.{$index}" => [
                "label" => "Tanggal lahir keluarga",
                "rules" => ["required_with[hubungan_keluarga.{$index},nama_keluarga.{$index}]"],
                'errors' => [
                    'required_with' => "Tanggal lahir keluarga harus diisi jika nama keluarga atau hubungan keluarga terisi."
                ]
            ]
        ];
    }

    private function getEmployeeInput(array $validated): array
    {
        return [
            "nama_karyawan" => $validated["nama_karyawan"],
            "tanggal_lahir_karyawan" => $validated["tanggal_lahir"],
            "alamat" => $validated["alamat"],
            "email" => $validated["email"],
            "valid_to" => $validated["valid_to"] ?: Time::now()->addYears(1)->format("Y-m-d"),
        ];
    }

    private function getFamiliesInput(int $count, array $validated, int $employeeID, bool $withID = false): array
    {
        $families = [];
        for ($i = 0; $i < $count; $i++) {
            $family = [
                "m_employee_id" => $employeeID,
                "hubungan_keluarga" => $validated["hubungan_keluarga"][$i],
                "nama_anggota_keluarga" => $validated["nama_keluarga"][$i],
                "tanggal_lahir_anggota_keluarga" => $validated["tanggal_lahir_keluarga"][$i],
            ];

            if (empty($family["hubungan_keluarga"]) && empty($family["nama_anggota_keluarga"]) && empty($family["tanggal_lahir_anggota_keluarga"])) {
                continue;
            }

            if ($withID) {
                $employeeFamilyIDs = $this->request->getPost("employee_family_id");
                $family["m_employee_family_id"] = $employeeFamilyIDs ? ($employeeFamilyIDs[$i] ?: null) : null;
            }

            $families[] = $family;
        }

        return $families;
    }
}
