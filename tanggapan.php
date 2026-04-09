<?php
include 'koneksi.php';
session_start();

// Cek apakah sudah login
if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit();
}

$id_user_session = $_SESSION['id'];
$role_user = $_SESSION['role'];

// --- LOGIKA NOTIFIKASI: HAPUS NOTIFIKASI SAAT HALAMAN DIBUKA ---
// Jika yang login adalah pelapor, tandai semua laporannya sebagai 'sudah dilihat'
if ($role_user == 'pelapor') {
    // Ambil dulu id_pelapor dari tabel pelapor berdasarkan id_user session
    $q_pelapor = mysqli_query($db, "SELECT id_pelapor FROM pelapor WHERE id_user = '$id_user_session'");
    if ($d_pelapor = mysqli_fetch_assoc($q_pelapor)) {
        $id_pelapor = $d_pelapor['id_pelapor'];
        // Update kolom dilihat_pelapor menjadi 1 agar notif di navbar hilang
        mysqli_query($db, "UPDATE pengaduan SET dilihat_pelapor = 1 WHERE id_pelapor = '$id_pelapor'");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Tanggapan | Desa Web</title>
  <link href="datatables.css" rel="stylesheet">
  <link href="style-custom.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .preview-link { cursor: pointer; transition: 0.2s; display: inline-block; }
    .preview-link:hover { opacity: 0.8; transform: scale(1.05); }
    .thumb-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    .modal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }
    main { padding-top: 20px; }
    .filter-wrapper { display: inline-block; margin-right: 15px; }
    .form-input-custom { border: 1px solid #dee2e6; margin-top: 10px; }
  </style>
</head>
<body>
  <?php
  // Navbar dipanggil setelah query update notifikasi di atas selesai
  include 'navbar.php';
  ?>

  <div class="container-fluid">
    <div class="row">
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="position: absolute; top: 30px; right: 0;">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Tanggapan</h1>
        </div>

        <?php
        // Logika Edit & Hapus
        if ($_POST) {
          if (isset($_POST['proses']) && $_POST['proses'] == 'edit') {
            $id_pengaduan = mysqli_real_escape_string($db, $_POST['id_pengaduan']);
            $tgl_kejadian = mysqli_real_escape_string($db, $_POST['tgl_kejadian']);
            $jenis_pengaduan = mysqli_real_escape_string($db, $_POST['jenis_pengaduan']);
            $isi_pengaduan = mysqli_real_escape_string($db, $_POST['isi_pengaduan']);
            $kategori = mysqli_real_escape_string($db, $_POST['kategori']);
            $status = mysqli_real_escape_string($db, $_POST['status']);
            
            $query_edit = mysqli_query($db, "UPDATE pengaduan SET 
              tgl_kejadian = '$tgl_kejadian', 
              jenis_pengaduan = '$jenis_pengaduan', 
              isi_pengaduan = '$isi_pengaduan', 
              kategori = '$kategori',
              status = '$status' 
              WHERE id_pengaduan = '$id_pengaduan'");

            if ($query_edit) {
              echo "<script>alert('Berhasil Update!'); window.location='tanggapan.php';</script>";
            }
          }
        }
        
        if (isset($_GET['proses']) && $_GET['proses'] == 'hapus') {
          $id = $_GET['id'];
          mysqli_query($db, "DELETE FROM pengaduan WHERE id_pengaduan = '$id' ");
          echo "<script>window.location='tanggapan.php';</script>";
        }
        ?>

        <table class="table table-striped" id="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal Lapor</th>
              <th>Tanggal Kejadian</th>
              <th>Foto/Video</th>
              <th>Jenis</th>
              <th>Kategori</th>
              <th>Isi</th>
              <th>Status</th>
              <th>Pelapor</th>
              <th>Tanggapan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($role_user == 'admin') {
                $query_string = "SELECT * FROM pengaduan WHERE status IN ('Selesai', 'Ditolak') ORDER BY id_pengaduan DESC";
            } else {
                $query_string = "SELECT * FROM pengaduan 
                                 WHERE id_pelapor = (SELECT id_pelapor FROM pelapor WHERE id_user = '$id_user_session') 
                                 AND status IN ('Selesai', 'Ditolak') 
                                 ORDER BY id_pengaduan DESC";
            }
            
            $query_pengaduan = mysqli_query($db, $query_string);
            $no = 1;
            while ($data = mysqli_fetch_assoc($query_pengaduan)) {
              $data_pelapor = mysqli_fetch_assoc(mysqli_query($db, "SELECT nama FROM pelapor WHERE id_pelapor = '$data[id_pelapor]'"));
              $file = $data['foto'];
              $file_url = "uploads/" . rawurlencode($file);
              $ekstensi = strtolower(pathinfo($file, PATHINFO_EXTENSION));
              $file_exists = (!empty($file) && file_exists("uploads/" . $file)) ? true : false;
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $data['tgl_pengaduan'] ?></td>
                <td><?= $data['tgl_kejadian'] ?></td>
                <td>
                  <div class="preview-link" data-bs-toggle="modal" data-bs-target="#preview-<?= $data['id_pengaduan'] ?>">
                    <?php if (!$file_exists): ?>
                      <span class="badge bg-danger">No File</span>
                    <?php elseif (in_array($ekstensi, ['mp4', 'webm', 'ogg'])): ?>
                      <span class="badge bg-info">Video</span>
                    <?php else: ?>
                      <img src="<?= $file_url ?>" class="thumb-img border">
                    <?php endif; ?>
                  </div>
                </td>
                <td><?= $data['jenis_pengaduan'] ?></td>
                <td><?= $data['kategori'] ?></td>
                <td><?= mb_strimwidth($data['isi_pengaduan'], 0, 20, "...") ?></td>
                <td>
                  <?php 
                  $bg = 'bg-secondary';
                  if($data['status'] == 'Proses') $bg = 'bg-warning text-dark';
                  if($data['status'] == 'Selesai') $bg = 'bg-success';
                  if($data['status'] == 'Ditolak') $bg = 'bg-danger';
                  ?>
                  <span class="badge <?= $bg ?>"><?= $data['status'] ?></span>
                </td>
                <td><?= $data_pelapor['nama'] ?></td>
                <td><?= $data['tanggapan'] ?></td>
                <td>
                  <a href="cetak_tanggapan.php?id=<?= $data['id_pengaduan'] ?>" target="_blank" class="btn btn-sm btn-outline-success">
                    <i data-feather="printer"></i>
                  </a> 
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </main>
      </div>
    </div>

    <?php 
    mysqli_data_seek($query_pengaduan, 0); 
    while ($data = mysqli_fetch_assoc($query_pengaduan)) { 
      $file = $data['foto'];
      $file_url = "uploads/" . rawurlencode($file);
      $ekstensi = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    ?>
    <div class="modal fade" id="preview-<?= $data['id_pengaduan'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0">
          <div class="modal-header border-0">
            <h6 class="modal-title text-white">Preview Media</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-0 text-center bg-black">
            <?php if (in_array($ekstensi, ['mp4', 'webm', 'ogg'])): ?>
              <video width="100%" controls><source src="<?= $file_url ?>" type="video/<?= $ekstensi ?>"></video>
            <?php else: ?>
              <img src="<?= $file_url ?>" class="img-fluid">
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script type="text/javascript" src="datatables.js"></script>

    <script type="text/javascript">
      feather.replace();
      const table = new DataTable('#table', { order: [] });
      // ... (Script filter tetap sama)
    </script>
  </body>
</html>
