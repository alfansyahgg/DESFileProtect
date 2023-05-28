<!-- left column -->
<!-- <h1 class="mt-3 ml-2"><?=$title;?></h1> -->
<div class="col-md-12 mt-4">
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Data Pasien</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="" method="POST">
            <div class=" card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Pasien</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Pasien"
                            value="<?=set_value('nama');?>">
                        <small class="form-text text-danger"><?=form_error('nama')?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                    <div class="col-sm-10">
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat Lahir"
                            value="<?=set_value('tempat_lahir');?>">
                        <small class="form-text text-danger"><?=form_error('tempat_lahir')?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="<?=set_value('tanggal_lahir');?>">
                        <small class="form-text text-danger"><?=form_error('tanggal_lahir')?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nomor HP Pasien</label>
                    <div class="col-sm-10">
                        <input type="number" name="nomor_hp" class="form-control" placeholder="Nomor HP Pasien"
                            value="<?=set_value('nomor_hp');?>">
                        <small class="form-text text-danger"><?=form_error('nomor_hp')?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat"
                            value="<?=set_value('alamat');?>">
                        <small class="form-text text-danger"><?=form_error('alamat')?></small>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Simpan</button>
                    <a href="<?=base_url('pengguna');?>" class="btn btn-secondary"><i
                            class="fas fa-arrow-circle-left"></i> Kembali</a>
                </div>
                <!-- /.card-footer -->
        </form>
    </div>
</div>
<!-- /.card-body -->