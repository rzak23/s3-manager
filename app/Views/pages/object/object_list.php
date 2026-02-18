<?php
/**
 * @var array $data
 * @var string $add_action
 * @var array $list_acl
 */
?>
<?= $this->extend('layouts/layout_dashboard') ?>

<?= $this->section('content') ?>
<main class="app-main">
    <div class="app-content-header"></div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <?php if(session()->has('error')): ?>
                    <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif ?>

                    <?php if(session()->has('success')): ?>
                    <div class="alert alert-success"><?= session('success') ?></div>
                    <?php endif ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col text-start">
                                    <h6 class="card-title">Daftar File</h6>
                                </div>
                                <div class="col text-end">
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#form-upload">
                                        <span>Upload File</span>
                                        <i class="bi bi-upload"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nama File</th>
                                    <th>Ukuran File</th>
                                    <th>Tgl. Update</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($data as $row): ?>
                                <tr>
                                    <td><?= $row['filename'] ?></td>
                                    <td><?= $row['size'] ?></td>
                                    <td><?= $row['last_date'] ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url($row['acl_action']) ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-shield"></i>
                                        </a>
                                        <a href="<?= site_url($row['download_action']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <a href="<?= site_url($row['hapus_action']) ?>" class="btn btn-sm btn-danger">
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
        </div>

        <!-- modal upload -->
        <div class="modal fade" role="dialog" id="form-upload">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="card-title">Upload File</h6>
                    </div>
                    <div class="modal-body">
                        <?= form_open_multipart($add_action) ?>
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="file-up">Upload File</label>
                                    <input type="file" class="form-control" name="file-up" id="file-up">
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="acl-file">ACL</label>
                                    <select name="acl-file" class="form-control" id="acl-file">
                                        <option>-- Pilih ACL --</option>
                                        <?php foreach($list_acl as $row => $key): ?>
                                        <option value="<?= $row ?>">
                                            <?= $key ?>
                                        </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-sm btn-success">
                                <span>Upload</span>
                                <i class="bi bi-upload"></i>
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
        <!-- modal upload -->
    </div>
</main>
<?= $this->endSection() ?>
