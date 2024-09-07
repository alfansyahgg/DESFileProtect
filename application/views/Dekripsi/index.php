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
<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <?php var_dump($pengguna); ?> -->
    <!-- /.card-header -->

    <!-- /.card-header -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-black">Dekripsi File </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Enkriptor</th>
                            <th>Waktu Enkripsi</th>
                            <th class="text-center">Jumlah Dekripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($file as $fl) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $fl['nama_user']; ?></td>
                                <td><?= date("d-m-Y h:i:s A", $fl['createdAt']) ?></td>
                                <td class="text-center"><?= $fl['jumlah_user']  ?> Users</td>
                                <!-- <td class="">
                                    <?php
                                    // if ($fl['status_dekripsi'] == 0) {
                                    //     $status = "danger";
                                    //     $status_name = "Belum";
                                    // } else {
                                    //     $status = "success";
                                    //     $status_name = "Selesai";
                                    // }
                                    ?>
                                    <span class="w-100 badge badge-<?= $status ?>"><?= $status_name ?></span>
                                </td> -->
                                <td class="d-flex justify-content-center" style="gap: 5px;">
                                    <?php if ($fl['jumlah_user'] > 0) : ?>
                                        <a class="btn btn-success" href="<?= base_url('dekripsi/detail/') . $fl['id_file']  ?>"><i class="fas fa-eye"></i></a>
                                    <?php endif; ?>
                                    <!-- <button data-toggle="modal" data-target="#modalDecrypt"
                                    class="btn btn-warning"><i class="fas fa-unlock"></i></button> -->
                                    <a href="<?= base_url('dekripsi/download/' . $fl['id_file']); ?>"><button type="button" class="btn btn-success" title="Download"><i class="fas fa-download"></i></button></a>
                                    <a href="<?= base_url('dekripsi/hapus/' . $fl['id_file']); ?>"><button type="button" class="btn btn-danger" title="Hapus" onclick="return confirm('Yakin hapus ?');"><i class="fas fa-trash-alt"></i></button></a>
                                </td>
                            </tr>

                            <div class="modal" id="modalDecrypt<?= $fl['id_file'] ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Fill password to decrypt</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="post" action="<?= base_url('dekripsi/dekrip/' . $fl['id_file']); ?>">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="">Password</label>
                                                    <input type="text" name="password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Solve</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- End of Main Content -->