<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-black">Dekripsi File <?= $users_file[0]['nama_file'] ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>File</th>
                            <th>Nama User</th>
                            <th>Email User</th>
                            <th>Waktu Dekrip</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($users_file as $fl) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $fl['nama_file']; ?></td>
                                <td><?= $fl['nama']; ?></td>
                                <td><?= $fl['email']; ?></td>
                                <td><?= date("d-m-Y h:i:s A", strtotime($fl['waktu_dekrip'])) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>