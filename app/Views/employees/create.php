<?= $this->extend("layout") ?>

<?= $this->section("pageTitle") ?>
Karyawan
<?= $this->endSection() ?>

<?= $this->section("title") ?>
Tambah Data Karyawan
<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="mb-4">
    <a href="/employees" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>
</div>

<form action="/employees" method="POST">
    <div class="mb-5">
        <h2 class="fs-4 mb-4">Biodata Karyawan : </h2>

        <div class="row mb-4 gy-3 text-md-end">
            <div class="col-md">
                <div class="row justify-content-md-end">
                    <label for="nama-karyawan" class="col-custom-label">Nama Karyawan</label>
                    <span class="col-md-1 col-form-label d-none d-md-block">:</span>
                    <div class="col-md-6">
                        <input type="text" id="nama-karyawan" name="nama_karyawan" class="form-control <?= hasValidationError("nama_karyawan") ? 'is-invalid' : '' ?>" value="<?= old("nama_karyawan") ?>">
                        <p class="invalid-feedback text-start"><?= getValidationError("nama_karyawan") ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="row justify-content-end">
                    <label for="tanggal-lahir" class="col-custom-label">Tanggal Lahir</label>
                    <span class="col-md-1 col-form-label d-none d-md-block">:</span>
                    <div class="col-md-6">
                        <input type="date" id="tanggal-lahir" name="tanggal_lahir" class="form-control <?= hasValidationError("tanggal_lahir") ? 'is-invalid' : '' ?>" value="<?= old("tanggal_lahir") ?>">
                        <p class=" invalid-feedback text-start"><?= getValidationError("tanggal_lahir") ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-md-end text-md-end mb-4">
            <div class="col-md-auto align-self-center">
                <div class="row">
                    <label for="alamat" class="col-custom-label">Alamat</label>
                    <span class="col-md-1 col-form-label d-none d-md-block">:</span>
                </div>
            </div>
            <div class="col-md-9">
                <textarea name="alamat" id="alamat" rows="7" class="form-control <?= hasValidationError("alamat") ? 'is-invalid' : '' ?>"><?= old("alamat") ?></textarea>
                <p class="invalid-feedback text-start"><?= getValidationError("alamat") ?></p>
            </div>
        </div>

        <div class="row mb-4 gy-3 text-md-end">
            <div class="col-md">
                <div class="row justify-content-md-end">
                    <label for="email" class="col-custom-label">Alamat email</label>
                    <span class="col-md-1 col-form-label d-none d-md-block">:</span>
                    <div class="col-md-6">
                        <input type="text" id="email" name="email" class="form-control <?= hasValidationError("email") ? 'is-invalid' : '' ?>" value="<?= old("email") ?>">
                        <p class="invalid-feedback text-start"><?= getValidationError("email") ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="row justify-content-end">
                    <label for="valid-to" class="col-custom-label">Valid hingga</label>
                    <span class="col-md-1 col-form-label d-none d-md-block">:</span>
                    <div class="col-md-6">
                        <input type="date" id="valid-to" name="valid_to" class="form-control <?= hasValidationError("valid_to") ? 'is-invalid' : '' ?>" value="<?= old("valid_to") ?>">
                        <p class=" invalid-feedback text-start"><?= getValidationError("valid_to") ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h2 class="fs-4 mb-4">Keluarga : </h2>

        <div class="table-responsive border border-secondary-subtle rounded pb-5" x-data="familyRelationship('<?= toJSON(retrieveOldsPost()) ?>', '<?= toJSON(retrieveValidationErrors()) ?>')">
            <input type="hidden" name="jumlah_keluarga" x-bind:value="elements.length">
            <table class="table-form">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Hubungan Keluarga</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                    </tr>
                </thead>
                <tbody>

                    <template x-for="element in elements" x-bind:key="element.id">
                        <tr x-data="{ 
                            hubunganKeluarga: getOld(element.id, 'hubunganKeluarga'),
                            namaKeluarga: getOld(element.id, 'namaKeluarga'),
                            tanggalLahirKeluarga: getOld(element.id, 'tanggalLahirKeluarga')
                        }" x-id="['hubungan-keluarga', 'nama-keluarga', 'tanggal-lahir-keluarga']">
                            <td>
                                <button type="button" class="btn btn-outline-primary" x-on:click="addOrRemove(element.id)">
                                    <i class="bi" x-bind:class="element.id === elements[0].id ? 'bi-plus' : 'bi-dash'"></i>
                                </button>
                            </td>
                            <td>
                                <select name="hubungan_keluarga[]" x-bind:id="$id('hubungan-keluarga')" class="form-select" x-bind:class="hasError(element.id, 'hubunganKeluarga') && 'is-invalid'" x-model="hubunganKeluarga">
                                    <option value="" selected>--Select--</option>
                                    <?php foreach ($familyRelationships as $familyRelationship) : ?>
                                        <option value="<?= $familyRelationship['value'] ?>">
                                            <?= $familyRelationship['label'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                                <p class="invalid-feedback text-start" x-text="getError(element.id, 'hubunganKeluarga')"></p>
                            </td>
                            <td>
                                <input type="text" name="nama_keluarga[]" x-bind:id="$id('nama-keluarga')" class="form-control" x-bind:class="hasError(element.id, 'namaKeluarga') && 'is-invalid'" x-model="namaKeluarga">
                                <p class="invalid-feedback text-start" x-text="getError(element.id, 'namaKeluarga')"></p>
                            </td>
                            <td>
                                <input type="date" name="tanggal_lahir_keluarga[]" x-bind:id="$id('tanggal-lahir-keluarga')" class="form-control" x-bind:value="getOld(element.id, 'tanggalLahirKeluarga')" x-bind:class="hasError(element.id, 'tanggalLahirKeluarga') && 'is-invalid'" x-model="tanggalLahirKeluarga">
                                <p class="invalid-feedback text-start" x-text="getError(element.id, 'tanggalLahirKeluarga')"></p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?= $this->endSection() ?>