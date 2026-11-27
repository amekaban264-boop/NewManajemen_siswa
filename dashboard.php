
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/koneksi.php';

$nama_admin = $_SESSION['nama'] ?? 'Administrator';

$total_siswa = 0;
$total_guru = 0;

$result_siswa = mysqli_query($conn, "SELECT COUNT(*) AS total FROM siswa");
if ($result_siswa) {
    $total_siswa = mysqli_fetch_assoc($result_siswa)['total'];
}

$result_guru = mysqli_query($conn, "SELECT COUNT(*) AS total FROM guru");
if ($result_guru) {
    $total_guru = mysqli_fetch_assoc($result_guru)['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | SchoolAdmin</title>

<link rel="stylesheet" href="assets/style.css">

<style>
/* Khusus sidebar dashboard */
.dashboard-sidebar {
    width: 250px;
    min-width: 250px;
    min-height: 100vh;
    background: #14213d;
    padding: 25px 18px;
    display: flex;
    flex-direction: column;
    color: #dbeafe;
}

.dashboard-sidebar .brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 40px;
}

.dashboard-sidebar .brand-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 12px;
    background: #3478f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: bold;
}

.dashboard-sidebar .brand h2 {
    margin: 0 0 5px;
    font-size: 20px;
    line-height: 1.3;
    color: white;
    white-space: nowrap;
}

.dashboard-sidebar .brand h2 span {
    color: #60a5fa;
}

.dashboard-sidebar .brand small {
    display: block;
    font-size: 11px;
    line-height: 1.5;
    color: #a9bddc;
}

.dashboard-sidebar .menu-label {
    margin: 0 0 15px;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 1.5px;
    color: #8da5ca;
}

.dashboard-sidebar nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.dashboard-sidebar nav a {
    display: flex;
    align-items: center;
    min-height: 48px;
    padding: 13px 15px;
    border-radius: 10px;
    color: #dbeafe;
    text-decoration: none;
    font-size: 13px;
    line-height: 1.5;
}

.dashboard-sidebar nav a:hover {
    background: #24395e;
    color: white;
}

.dashboard-sidebar nav a.active {
    background: #3478f6;
    color: white;
    font-weight: 600;
}

.dashboard-sidebar .sidebar-bottom {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid #34445f;
}

.dashboard-sidebar .admin-mini {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    color: white;
}

.dashboard-sidebar .logout {
    display: block;
    padding: 10px;
    color: #ff8585;
    text-decoration: none;
}

.dashboard-sidebar .logout:hover {
    color: white;
}

/* Dashboard content */
.dashboard-content {
    flex: 1;
    min-width: 0;
    padding: 30px;
    background: #f3f6fc;
}

.dashboard-banner {
    background: linear-gradient(110deg, #2864ef, #4c8df7);
    color: white;
    border-radius: 18px;
    padding: 35px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-banner h1 {
    font-size: 25px;
    margin: 15px 0 10px;
}

.dashboard-banner p {
    font-size: 13px;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.dashboard-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(20, 40, 80, 0.04);
}

.dashboard-card h3 {
    font-size: 18px;
    margin-bottom: 12px;
}

.dashboard-card .number {
    font-size: 30px;
    font-weight: bold;
    color: #172b4d;
    margin-bottom: 12px;
}

.dashboard-card a {
    color: #3478f6;
    text-decoration: none;
}

.dashboard-description {
    background: white;
    border-radius: 16px;
    padding: 25px;
}

.dashboard-description h2 {
    font-size: 19px;
    margin-bottom: 10px;
}

.dashboard-description p {
    color: #64748b;
    line-height: 1.7;
}

.dashboard-footer {
    text-align: center;
    padding: 25px 0;
    color: #94a3b8;
    font-size: 11px;
}

@media (max-width: 700px) {
    .dashboard-sidebar {
        width: 200px;
        min-width: 200px;
    }

    .dashboard-content {
        padding: 18px;
    }

    .dashboard-cards {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<div class="dashboard">

    <aside class="dashboard-sidebar">

        <div class="brand">
            <div class="brand-icon">S</div>

            <div>
                <h2>School<span>Admin</span></h2>
                <small>Management System</small>
            </div>
        </div>

        <div class="menu-label">MENU UTAMA</div>

        <nav>
            <a href="dashboard.php" class="active">▦ Dashboard</a>
            <a href="data_siswa.php">♙ Data Siswa</a>
            <a href="data_guru.php">▤ Data Guru</a>
            <a href="absensi.php">◷ Absensi</a>
            <a href="rekap_absensi.php">📊 Rekap Absensi</a>
            <a href="profil_admin.php">👤 Profil Admin</a>
        </nav>

        <div class="sidebar-bottom">
            <div class="admin-mini">
                <div class="avatar">A</div>

                <div>
                    <strong><?= htmlspecialchars($nama_admin); ?></strong>
                    <br>
                    <small>Administrator</small>
                </div>
            </div>

            <a href="logout.php" class="logout">↪ Keluar</a>
        </div>

    </aside>

    <main class="dashboard-content">

        <section class="dashboard-banner">
            <div>
                <small>SCHOOL MANAGEMENT SYSTEM</small>

                <h1>Selamat Datang, <?= htmlspecialchars($nama_admin); ?>! 👋</h1>

                <p>Kelola informasi sekolah dengan mudah melalui SchoolAdmin.</p>
            </div>

            <div style="font-size:55px;">🏫</div>
        </section>

        <section class="dashboard-cards">

            <div class="dashboard-card">
                <h3>👨‍🎓 Total Siswa</h3>

                <div class="number">
                    <?= $total_siswa; ?>
                </div>

                <a href="data_siswa.php">Lihat Data Siswa →</a>
            </div>

            <div class="dashboard-card">
                <h3>👨‍🏫 Total Guru</h3>

                <div class="number">
                    <?= $total_guru; ?>
                </div>

                <a href="data_guru.php">Lihat Data Guru →</a>
            </div>

        </section>

        <section class="dashboard-description">
            <h2>Menu Pengelolaan Sekolah</h2>

            <p>
                Gunakan menu di sebelah kiri untuk mengelola data siswa,
                guru, absensi, rekap absensi, dan profil admin.
            </p>
        </section>

        <footer class="dashboard-footer">
            © <?= date('Y'); ?> SchoolAdmin. Sistem Manajemen Sekolah.
        </footer>

    </main>

</div>

</body>
</html>
