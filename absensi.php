
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/koneksi.php';

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$pesan = "";

// Menyimpan absensi
if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $siswa_id = $_POST['siswa_id'] ?? [];
    $status = $_POST['status'] ?? [];
    $keterangan = $_POST['keterangan'] ?? [];

    $query = mysqli_prepare($conn, "
        INSERT INTO absensi
            (siswa_id, tanggal, status, keterangan)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            status = VALUES(status),
            keterangan = VALUES(keterangan)
    ");

    foreach ($siswa_id as $i => $id) {
        $id = (int)$id;
        $statusSiswa = $status[$i] ?? 'Hadir';
        $keteranganSiswa = trim($keterangan[$i] ?? '');

        mysqli_stmt_bind_param(
            $query,
            "isss",
            $id,
            $tanggal,
            $statusSiswa,
            $keteranganSiswa
        );

        mysqli_stmt_execute($query);
    }

    mysqli_stmt_close($query);

    header("Location: absensi.php?tanggal=" . urlencode($tanggal) . "&sukses=1");
    exit;
}

if (isset($_GET['sukses'])) {
    $pesan = "Absensi berhasil disimpan!";
}

// Mengambil data siswa dan absensi
$sql = "
    SELECT
        siswa.id,
        siswa.nis,
        siswa.nama,
        siswa.kelas,
        absensi.status,
        absensi.keterangan
    FROM siswa
    LEFT JOIN absensi
        ON siswa.id = absensi.siswa_id
        AND absensi.tanggal = ?
    ORDER BY siswa.nama ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $tanggal);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_siswa = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Absensi Siswa | SchoolAdmin</title>

<link rel="stylesheet" href="assets/style.css">

<style>
.attendance-input {
    width: 100%;
    min-width: 110px;
    padding: 9px;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-sizing: border-box;
    background: white;
}

.attendance-note {
    min-width: 180px;
}

.attendance-date {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin: 10px 0 20px;
}

.success-message {
    background: #e7f8ed;
    color: #218447;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.table-responsive table {
    width: 100%;
    min-width: 750px;
    border-collapse: collapse;
}

.table-responsive th {
    background: #f7f9fc;
    color: #68758a;
    font-size: 12px;
    padding: 15px 12px;
    text-align: left;
}

.table-responsive td {
    padding: 13px 12px;
    border-bottom: 1px solid #edf0f5;
    font-size: 13px;
}

.table-responsive tbody tr:hover {
    background: #fafcff;
}

.attendance-info {
    margin-bottom: 15px;
    color: #718096;
    font-size: 13px;
}

.empty-message {
    text-align: center;
    padding: 30px !important;
    color: #8b96a8;
}

.save-button {
    margin-top: 20px;
    padding: 12px 22px;
    border: none;
    border-radius: 8px;
    background: #3478f6;
    color: white;
    font-weight: 600;
    cursor: pointer;
}

.save-button:hover {
    background: #2864df;
}
</style>
</head>

<body>

<div class="dashboard">

    <?php include 'sidebar.php'; ?>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h1>Absensi Siswa</h1>
                <p>Kelola kehadiran siswa sekolah.</p>
            </div>

            <div class="admin-profile">
                <div class="avatar">A</div>
                <div>
                    <strong>
                        <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?>
                    </strong>
                    <small>Administrator</small>
                </div>
            </div>
        </header>

        <section class="welcome">
            <div>
                <span class="welcome-tag">
                    SCHOOL MANAGEMENT SYSTEM
                </span>

                <h2>Absensi Siswa 📋</h2>

                <p>Catat kehadiran siswa dengan mudah.</p>
            </div>

            <div class="welcome-icon">📝</div>
        </section>

        <section class="content-card">

            <div class="section-header">
                <div>
                    <h2>Daftar Absensi</h2>
                    <p>Kelola kehadiran dan keterangan siswa.</p>
                </div>
            </div>

            <?php if ($pesan != ""): ?>
                <div class="success-message">
                    <?= htmlspecialchars($pesan); ?>
                </div>
            <?php endif; ?>

            <form method="GET">
                <label for="tanggal">
                    <b>Tanggal Absensi</b>
                </label>

                <br>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    class="attendance-date"
                    value="<?= htmlspecialchars($tanggal); ?>"
                    required
                    onchange="this.form.submit()"
                >
            </form>

            <div class="attendance-info">
                Jumlah siswa: <?= $total_siswa; ?>
            </div>

            <form method="POST">

                <input
                    type="hidden"
                    name="tanggal"
                    value="<?= htmlspecialchars($tanggal); ?>"
                >

                <div class="table-responsive">

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if ($total_siswa > 0): ?>

                            <?php $no = 1; ?>

                            <?php while ($siswa = mysqli_fetch_assoc($result)): ?>

                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($siswa['nis']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($siswa['nama']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($siswa['kelas']); ?>
                                    </td>

                                    <td>
                                        <input
                                            type="hidden"
                                            name="siswa_id[]"
                                            value="<?= (int)$siswa['id']; ?>"
                                        >

                                        <select
                                            name="status[]"
                                            class="attendance-input"
                                            required
                                        >
                                            <?php
                                            $statusSekarang = $siswa['status'] ?? 'Hadir';
                                            $pilihan = ['Hadir', 'Izin', 'Sakit', 'Alpa'];

                                            foreach ($pilihan as $pilih):
                                            ?>

                                                <option
                                                    value="<?= $pilih; ?>"
                                                    <?= $statusSekarang == $pilih ? 'selected' : ''; ?>
                                                >
                                                    <?= $pilih; ?>
                                                </option>

                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td>
                                        <input
                                            type="text"
                                            name="keterangan[]"
                                            class="attendance-input attendance-note"
                                            placeholder="Masukkan keterangan..."
                                            value="<?= htmlspecialchars($siswa['keterangan'] ?? ''); ?>"
                                        >
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="empty-message">
                                    Belum ada data siswa.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>
                    </table>

                </div>

                <?php if ($total_siswa > 0): ?>

                    <button
                        type="submit"
                        name="simpan"
                        class="save-button"
                    >
                        Simpan Absensi
                    </button>

                <?php endif; ?>

            </form>

        </section>

        <footer>
            © <?= date('Y'); ?> SchoolAdmin. Sistem Manajemen Sekolah.
        </footer>

    </main>
</div>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
?>
