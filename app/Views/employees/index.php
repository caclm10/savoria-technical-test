<?= $this->extend("layout") ?>

<?= $this->section("pageTitle") ?>
Karyawan
<?= $this->endSection() ?>

<?= $this->section("title") ?>
Daftar Data Karyawan
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="d-flex mb-4">
    <div class="flex-grow-1"></div>

    <a href="/employees/new" class="btn btn-primary">
        <i class="bi bi-plus"></i>
        Karyawan
    </a>
</div>

<?php if (count($employees) === 0) : ?>
    <p class="text-center text-secondary fst-italic">Belum ada data karyawan.</p>
<?php else : ?>

    <div class="modal fade" tabindex="-1" id="delete-employee-modal" aria-labelledby="delete-employee-modal-label" aria-hidden="true" x-data="{ deleteID: null }" x-on:set-delete-id.window="deleteID = $event.detail">
        <div class="modal-dialog">
            <div class="modal-content" x-id="['delete-form']">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-employee-modal-label">Hapus data karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin? Data karyawan yang dihapus tidak dapat dikembalikan.</p>
                    <form method="POST" x-bind:action="`/employees/${deleteID}/delete`" x-bind:id="$id('delete-form')" x-on:submit="$refs.submitBtn.disabled = true"></form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" x-bind:form="$id('delete-form')" x-ref="submitBtn">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Email</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $index => $employee) : ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $employee['nama'] ?></td>
                        <td><?= $employee['email'] ?></td>
                        <td><?= $employee['alamat'] ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Employee action">
                                <a href="/employees/<?= $employee["id"] ?>" class="btn btn-outline-secondary" x-data x-init="tooltip($el, 'Lihat data karyawan')">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/employees/<?= $employee["id"] ?>/edit" class="btn btn-outline-primary" x-data x-init="tooltip($el, 'Edit data karyawan')">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-employee-modal" x-data x-init="tooltip($el, 'Hapus data karyawan')" x-on:click="$dispatch('set-delete-id', <?= $employee["id"] ?>)">
                                    <i class="bi bi-trash2"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
<?= $this->endSection() ?>