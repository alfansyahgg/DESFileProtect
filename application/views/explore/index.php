<?php if (!empty($this->session->flashdata('berhasil'))) { ?>
<script>
$(document).ready(function() {
    Swal.fire('Status', 'Berhasil!', 'success')
})
</script>
<?php } ?>

<?php if (!empty($this->session->flashdata('gagal'))) { ?>
<script>
$(document).ready(function() {
    Swal.fire('Status', 'Gagal!', 'warning')
})
</script>
<?php } ?>

<?php if (!empty($this->session->flashdata('password'))) { ?>
<script>
$(document).ready(function() {
    Swal.fire('Status', 'Gagal: Kunci Salah!', 'warning')
})
</script>
<?php } ?>

<?php if (!empty($this->session->flashdata('waktu'))) { ?>
<script>
$(document).ready(function() {
    Swal.fire('Status', 'Gagal: Expired!', 'warning')
})
</script>
<?php } ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>

    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- DATA Files -->
        <?php foreach ($explore as $item) : ?>

        <?php
            $now = time();
            $due = strtotime($item['batas']);
            $expired = $now > $due;
            ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <img class="card-img-top" style="height: 150px;" src="
                <?php
                $extension = explode(".", $item['nama_file']);
                $extension = end($extension);
                if ($extension == "pdf") {
                    echo base_url('assets/img/pdf.jpg');
                } else {
                    echo base_url('assets/img/excel.jpg');
                }

                ?>" alt="Card image cap">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <h5 class="text-primary"><?= $item['nama_file'] ?></h5>
                                <p><?= date('d F Y H:i:s', strtotime($item['batas'])) ?>
                                    <?php if ($expired) : ?>
                                    <small class="text-danger">Expired</small>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <button data-toggle="modal" data-target="#modalDownload<?= $item['id_file'] ?>"
                                class="btn btn-success btn-block mt-2">Download</button>
                        </div>
                    </div>

                    <h4 class="font-weight-bold text-primary"></h4>
                </div>
            </div>
        </div>

        <div class="modal" id="modalDownload<?= $item['id_file'] ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Fill password to decrypt</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="downloadForm" method="post" action="<?= base_url('explore/dekrip/') ?>">
                        <div class="modal-body">
                            <input type="hidden" name="id_file" value="<?= $item['id_file'] ?>" class="form-control">
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="text" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-solve">Solve</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>
</div>
<!-- /.container-fluid -->
<!-- End of Main Content -->
<script>
$(document).ready(function() {
    $(".btn-solve").on('click', function() {
        setTimeout(() => {
            window.location.href = window.location.href;
        }, 1000);
    })
})
</script>