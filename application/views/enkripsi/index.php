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
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- /.card-header -->

    <!-- /.card-header -->

    <div class="card card-info mt-3">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-black">Data Enkripsi</h6>
            </div>
        </div>
        <div class="card-body">
            <?php echo validation_errors(); ?>

            <?php echo form_open_multipart('/enkripsi/import'); ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Input File</label>
                <div class="col-sm-10">
                    <input class="form-control" type="file" name="file" id="file">
                    <?php if (isset($pesan)) {
                        echo $pesan;
                    }
                    ?>
                </div>
                <div class="col-sm-10">

                    <small>
                        *PDF & Excel
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Batas</label>
                <div class="col-sm-10">

                    <input required type="datetime-local" name="batas" class="form-control" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">

                    <input required type="text" name="password" class="form-control" placeholder="Password" value="">
                </div>
            </div>

            <button type="submit" formaction="<?= base_url('Enkripsi/import') ?>" class="btn btn-info"><i
                    class="fas fa-lock"></i> Enkripsi</button>

            <?= form_close() ?>
        </div>
    </div>

</div>
</div>