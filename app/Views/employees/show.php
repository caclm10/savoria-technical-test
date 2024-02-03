<?= $this->extend("layout") ?>

<?= $this->section("pageTitle") ?>
Karyawan
<?= $this->endSection() ?>

<?= $this->section("title") ?>
Data Karyawan
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="modal fade" tabindex="-1" id="delete-employee-modal" aria-labelledby="delete-employee-modal-label" aria-hidden="true" x-data>
    <div class="modal-dialog">
        <div class="modal-content" x-id="['delete-form']">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-employee-modal-label">Hapus data karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin? Data karyawan yang dihapus tidak dapat dikembalikan.</p>
                <form method="POST" action="/employees/<?= $employee["id"] ?>/delete" x-bind:id="$id('delete-form')"></form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" x-bind:form="$id('delete-form')">Hapus</button>
            </div>
        </div>
    </div>
</div>

<div class="mb-2">
    <a href="/employees" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>
</div>

<div class="d-flex align-items-center mb-4">
    <div class="flex-grow-1"></div>

    <a href="/employees/<?= $employee["id"] ?>/edit" class="btn btn-outline-primary me-3" x-data x-init="tooltip($el, 'Edit data karyawan')">
        <i class="bi bi-pencil"></i>
    </a>
    <button type="button" class="btn btn-outline-danger" x-data x-init="tooltip($el, 'Hapus data karyawan')" data-bs-toggle="modal" data-bs-target="#delete-employee-modal">
        <i class="bi bi-trash2"></i>
    </button>
</div>

<div class="mb-5">
    <h2 class="fs-4 mb-4">Biodata Karyawan</h2>
    <div class="row row-cols-1 row-cols-sm-2">
        <div class="col">
            <p class="mb-1 fw-bold">Nama Karyawan</p>
            <p><?= $employee["nama"] ?></p>
        </div>
        <div class="col">
            <p class="mb-1 fw-bold">Tanggal Lahir</p>
            <p><?= idDateFormat($employee["tanggal_lahir"]) ?> (<?= calculateAge($employee["tanggal_lahir"]) ?> tahun)</p>
        </div>
        <div class="col">
            <p class="mb-1 fw-bold">Alamat email</p>
            <p><?= $employee["email"] ?></p>
        </div>
        <div class="col">
            <p class="mb-1 fw-bold">Alamat</p>
            <p><?= $employee["alamat"] ?></p>
        </div>
        <div class="col">
            <p class="mb-1 fw-bold">Valid Hingga</p>
            <p><?= idDateFormat($employee["valid_to"]) ?></p>
        </div>
    </div>
</div>

<div>
    <h2 class="fs-4 mb-4">Anggota Keluarga</h2>
    <div class="table-responsive">
        <table class="table-detail">
            <thead>
                <th scope="col">Hubungan</th>
                <th scope="col">Nama</th>
                <th scope="col">Tanggal Lahir</th>
            </thead>
            <tbody>
                <?php foreach ($employee["keluarga"] as $family) : ?>
                    <tr>
                        <td><?= $familyRelationships[$family["hubungan"]] ?></td>
                        <td><?= $family["nama"] ?></td>
                        <td><?= $family["tanggal_lahir"] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>