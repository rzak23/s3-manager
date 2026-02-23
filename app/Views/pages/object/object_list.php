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
                                        <!--<a href="--><?php //= site_url($row['acl_action']) ?><!--" class="btn btn-sm btn-info">-->
                                        <!--    <i class="bi bi-shield"></i>-->
                                        <!--</a>-->
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
                        <!-- Loading Overlay -->
                        <div id="upload-loading" style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.85); z-index:9999; border-radius:8px;">
                            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center;">
                                <div class="spinner-border text-success mb-2" role="status" style="width:3rem; height:3rem;"></div>
                                <div id="upload-progress-wrapper" class="mt-2" style="width:220px;">
                                    <div class="progress mb-1" style="height:10px;">
                                        <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width:0%"></div>
                                    </div>
                                    <small id="upload-progress-text" class="text-muted">Mempersiapkan upload...</small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Upload menggunakan tag biasa, bukan form_open_multipart -->
                        <?= form_open_multipart($add_action, ['id' => 'form-upload-file']) ?>
                            <div class="mb-3">
                                <label class="form-label" for="folder">
                                    <span>Folder/Key</span>
                                    <small class="text-info">Kosongkan jika tidak ingin menambahkan key</small>
                                </label>
                                <input type="text" name="folder" class="form-control" id="folder" autocomplete="off">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="file-up">
                                            <span>Upload File</span>
                                        </label>
                                        <input type="file" class="form-control" name="file-up" id="file-up">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="acl-file">ACL</label>
                                        <select name="acl-file" class="form-control" id="acl-file">
                                            <option>-- Pilih ACL --</option>
                                            <?php foreach($list_acl as $row => $key): ?>
                                                <option value="<?= $row ?>"><?= $key ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Alert area -->
                            <div id="upload-alert" class="mb-2" style="display:none;"></div>

                            <div class="mb-3">
                                <button type="submit" id="btn-upload" class="btn btn-sm btn-success">
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

<?= $this->section('content-js') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form        = document.getElementById('form-upload-file');
        const loading     = document.getElementById('upload-loading');
        const progressBar = document.getElementById('upload-progress-bar');
        const progressTxt = document.getElementById('upload-progress-text');
        const alertBox    = document.getElementById('upload-alert');
        const btnUpload   = document.getElementById('btn-upload');
        const modal       = document.getElementById('form-upload');

        // Reset state saat modal dibuka ulang
        modal.addEventListener('show.bs.modal', function () {
            form.reset();
            alertBox.style.display = 'none';
            alertBox.innerHTML     = '';
            resetProgress();
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validasi sederhana
            const fileInput = document.getElementById('file-up');
            const aclInput  = document.getElementById('acl-file');

            if (!fileInput.files.length) {
                showAlert('danger', 'Silakan pilih file terlebih dahulu.');
                return;
            }
            if (aclInput.value === '-- Pilih ACL --') {
                showAlert('danger', 'Silakan pilih ACL terlebih dahulu.');
                return;
            }

            const formData = new FormData(form);

            // Tampilkan loading
            loading.style.display    = 'block';
            btnUpload.disabled       = true;
            alertBox.style.display   = 'none';
            resetProgress();

            const xhr = new XMLHttpRequest();

            // Update progress bar realtime
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    const pct = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = pct + '%';
                    progressBar.setAttribute('aria-valuenow', pct);

                    if (pct < 100) {
                        progressTxt.textContent = `Mengupload... ${pct}%`;
                    } else {
                        progressTxt.textContent = 'Memproses di server...';
                    }
                }
            });

            xhr.addEventListener('load', function () {
                loading.style.display = 'none';
                btnUpload.disabled    = false;

                if (xhr.status === 200) {
                    let response;
                    try {
                        response = JSON.parse(xhr.responseText);
                    } catch (err) {
                        // Jika controller redirect (non-JSON), anggap sukses
                        showAlert('success', 'File berhasil diupload!');
                        setTimeout(() => location.reload(), 1500);
                        return;
                    }

                    if (response.status === 'success') {
                        showAlert('success', response.message ?? 'File berhasil diupload!');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('danger', response.message ?? 'Upload gagal, coba lagi.');
                    }
                } else {
                    showAlert('danger', `Terjadi kesalahan server (HTTP ${xhr.status}).`);
                }
            });

            xhr.addEventListener('error', function () {
                loading.style.display = 'none';
                btnUpload.disabled    = false;
                showAlert('danger', 'Koneksi gagal, periksa jaringan Anda.');
            });

            xhr.open('POST', form.action);
            // Jika CI4 CSRF pakai header:
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });

        function showAlert(type, message) {
            alertBox.innerHTML     = `<div class="alert alert-${type} alert-sm py-2 mb-0">${message}</div>`;
            alertBox.style.display = 'block';
        }

        function resetProgress() {
            progressBar.style.width    = '0%';
            progressTxt.textContent    = 'Mempersiapkan upload...';
        }
    });
</script>
<?= $this->endSection() ?>
