<div class="container vh-100">

    <!-- Outer Row -->
    <div class="row justify-content-center vh-100">

        <div class="col-lg-12 my-auto">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h2 font-weight-bold text-gray-900 mb-4">Aplikasi Enkrip/Dekrip DES</h1>

                                    <img src="<?= base_url('assets') ?>/img/data-encryption.png" alt="Aplikasi Raport Logo" class="mb-4" width="72px">
                                </div>
                                <?= $this->session->flashdata('massage'); ?>
                                <form class="user" method="POST" action="<?= base_url('auth') ?>">
                                    <div class="form-group">
                                        <div class="row ">
                                            <div class="col-12 text-center">
                                                <p class="h5 text-gray-900 mb-4">Login</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row ">
                                            <div class="col-3">
                                                <span>Email</span>
                                            </div>
                                            <div class="col-9">
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?= set_value('email'); ?>">
                                                <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-3">
                                                <span>Password</span>
                                            </div>
                                            <div class="col-9">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                                <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block col-12 ">
                                        Masuk
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a href="<?= base_url('auth/register') ?>">Register</a>
                                </div>
                                <!-- <div class="text-center">
                                    <a class="small" href="<?= base_url('auth/register'); ?>">Buat Akun Baru ?</a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>