<?php
/**
 * @var array $data
 */
?>
<?= $this->extend('layouts/layout_dashboard') ?>

<?= $this->section('content') ?>
<main class="app-main">
    <div class="app-content-header"></div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-start">
                                <h6 class="card-title">Daftar Bucket</h6>
                            </div>
                            <div class="col text-end">
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#form-bucket">
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Bucket</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data as $row): ?>
                            <tr>
                                <td><?= $row['nama'] ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('bucket/hapus/'.$row['nama']) ?>" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" role="dialog" id="form-bucket">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Tambah Bucket</h6>
                    </div>
                    <div class="modal-body">
                        <?= form_open('bucket/save') ?>
                        <div class="mb-3">
                            <label class="form-label" for="nama-bucket">Nama Bucket</label>
                            <input type="text" name="nama-bucket" class="form-control" id="nama-bucket" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-sm btn-success">
                                <span>Simpan</span>
                                <i class="bi bi-floppy-disk"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                                <span>Batal</span>
                            </button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
