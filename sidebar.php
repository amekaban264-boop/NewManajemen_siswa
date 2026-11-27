
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <div class="brand">
        <div class="brand-icon">S</div>
        <div>
            <h2>School<span>Admin</span></h2>
            <small>Management System</small>
        </div>
    </div>

    <div class="menu-label">MENU UTAMA</div>

    <nav>
        <a href="dashboard.php"
           class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            ▦ Dashboard
        </a>

        <a href="data_siswa.php"
           class="<?= $current_page == 'data_siswa.php' ? 'active' : '' ?>">
            ♙ Data Siswa
        </a>

        <a href="data_guru.php"
           class="<?= $current_page == 'data_guru.php' ? 'active' : '' ?>">
            ▤ Data Guru
        </a>

        <a href="absensi.php"
           class="<?= $current_page == 'absensi.php' ? 'active' : '' ?>">
            ◷ Absensi
        </a>

        <a href="rekap_absensi.php"
           class="<?= $current_page == 'rekap_absensi.php' ? 'active' : '' ?>">
            📊 Rekap Absensi
        </a>

        <a href="profil_admin.php"
           class="<?= $current_page == 'profil_admin.php' ? 'active' : '' ?>">
            👤 Profil Admin
        </a>
    </nav>

    <div class="sidebar-bottom">
        <a href="logout.php" class="logout">↪ Keluar</a>
    </div>
</aside>
