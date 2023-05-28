<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- /.card-header -->

    <div class="card card-info mt-3">

        <!-- /.card-header -->

        <div class="card card-info mt-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-black">Data Pasien</h6>
                </div>
                <div class="card-body">
                    <a href="<?=base_url('pasien/tambah')?>" class="btn btn-primary">
                        <i class="fa fa-edit"></i> Tambah Pasien</a>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>TTL</th>
                                    <th>Nomor HP</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
foreach ($pasien as $item) {?>
                                <tr>
                                    <td><?=$no++;?></td>
                                    <td><?=$item['nama'];?></td>
                                    <td><?=$item['tempat_lahir'] . ', ' . date('d F Y', strtotime($item['tanggal_lahir']));?>
                                    </td>
                                    <td><?=$item['nomor_hp'];?></td>
                                    <td><?=$item['alamat'];?></td>
                                    <td>
                                        <!-- <a href="<?=base_url('domisili/detail/' . $dmsl['id_domisili']);?>"> <button type="button" class="btn btn-info" title="Detail"><i class="fas fa-user"></i></button></a> -->
                                        <a href="<?=base_url('pasien/ubah/' . $item['id_pasien']);?>"><button
                                                type="button" class="btn btn-success" title="Ubah"><i
                                                    class="fas fa-edit"></i></button></a>
                                        <!-- <a href="<?=base_url('pasien/changepassword/' . $item['id_pasien']);?>"><button type="button" class="btn btn-warning" title="Ubah"><i class="fas fa-key"></i></button></a> -->
                                        <a href="<?=base_url('pasien/hapus/' . $item['id_pasien']);?>"><button
                                                type="button" class="btn btn-danger" title="Hapus"
                                                onclick="return confirm('Yakin hapus ?');"><i
                                                    class="fas fa-trash-alt"></i></button></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
<!-- End of Main Content -->