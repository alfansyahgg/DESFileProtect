<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-pink sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon ">
            <img src="<?= base_url('assets'); ?>/img/data-encryption.png" width="30px">
        </div>
        <div class="sidebar-brand-text mx-3">DES School Share</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <?php if ($this->session->role_id == 2) : ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('enkripsi') ?>">
                <i class="fas fa-fw fa-lock"></i>
                <span>Enkripsi</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('dekripsi') ?>">
                <i class="fas fa-fw fa-unlock"></i>
                <span>Dekripsi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('pengguna') ?>">
                <i class="fas fa-fw fa-user"></i>
                <span>Users</span>
            </a>
        </li>



    <?php else : ?>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('user') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>


        <!-- <li class="nav-item">
        <a class="nav-link" href="<?= base_url('dekripsi') ?>">
            <i class="fas fa-fw fa-lock"></i>
            <span>Dekripsi</span>
        </a>
    </li> -->
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('Explore') ?>">
                <i class="fas fa-fw fa-lock"></i>
                <span>Explore</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('Explore/myfile') ?>">
                <i class="fas fa-fw fa-lock"></i>
                <span>My File</span>
            </a>
        </li>
    <?php endif; ?>

    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->





    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->