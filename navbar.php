<?php
// Deteksi nama file yang sedang aktif
$current_page = basename($_SERVER['PHP_SELF']);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'koneksi.php'; 

$id_user_login = $_SESSION['id'];
$role_user = $_SESSION['role'];
$nama_tampilan = "Member"; 
$id_pelapor_login = 0;

if (isset($id_user_login)) {
    $query_user = mysqli_query($db, "SELECT nama, id_pelapor FROM pelapor WHERE id_user = '$id_user_login'");
    if ($data_user = mysqli_fetch_assoc($query_user)) {
        $nama_tampilan = $data_user['nama'];
        $id_pelapor_login = $data_user['id_pelapor'];
    } else {
        $nama_tampilan = $_SESSION['nama'] ?? 'Admin'; 
    }
}

// --- HITUNG NOTIFIKASI PERMANEN ---
$count_notif = 0;

if ($role_user == 'admin') {
    // Admin: Tetap muncul selama status Antri atau Proses (tidak hilang sampai ditinjau lanjut)
    $q_notif = mysqli_query($db, "SELECT COUNT(*) as total FROM pengaduan WHERE status IN ('Antri', 'Proses')");
    $row_notif = mysqli_fetch_assoc($q_notif);
    $count_notif = $row_notif['total'];
} else {
    // Pelapor: Notif HANYA muncul jika status sudah direspon admin DAN dilihat_pelapor masih 0
    // Notif ini akan otomatis 0 karena di halaman tanggapan.php kita sudah melakukan UPDATE query dilihat_pelapor = 1
    $q_notif = mysqli_query($db, "SELECT COUNT(*) as total FROM pengaduan 
                                 WHERE id_pelapor = '$id_pelapor_login' 
                                 AND status != 'Antri' 
                                 AND dilihat_pelapor = 0");
    $row_notif = mysqli_fetch_assoc($q_notif);
    $count_notif = $row_notif['total'];
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Desa Web</title>
  
  <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="dashboard.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" type="text/css" href="hp.css">
  
  <style>
    .bi { width: 1.2rem; height: 1.2rem; }
    .nav-link.active {
      color: #000 !important;
      background-color: rgba(0, 0, 0, 0.1);
      font-weight: bold;
    }
    .badge-notif {
      font-size: 0.7rem;
      padding: 0.35em 0.65em;
      border-radius: 50rem;
    }
    .bg-proses { background-color: #ffc107; color: #000; } 
  </style>
</head>
<body>

  <header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white d-flex align-items-center" href="index.php">
      <i class="bi bi-buildings-fill me-2"></i> 
      Desa Web
    </a>

    <ul class="navbar-nav flex-row d-md-none">
      <li class="nav-item text-nowrap">
        <button class="nav-link px-3 text-white position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
          <i class="bi bi-list fs-3"></i>
          <?php if($count_notif > 0): ?>
            <span class="position-absolute top-0 start-50 translate-middle-x badge border border-light rounded-circle bg-danger p-1" style="margin-top: 10px;"></span>
          <?php endif; ?>
        </button>
      </li>
    </ul>
  </header>

  <div class="container-fluid">
    <div class="row">
      <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
        <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu">

          <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">Menu Navigasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
          </div>

          <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">

            <div class="d-flex align-items-center px-4 py-3 border-bottom mb-2">
              <div class="flex-shrink-0">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                  <i class="bi bi-person-fill"></i>
                </div>
              </div>
              <div class="flex-grow-1 ms-3 overflow-hidden">
                <h6 class="mb-0 text-truncate fw-bold text-dark"><?php echo $nama_tampilan; ?></h6>
                <small class="text-muted text-capitalize"><?php echo $role_user ?? 'None'; ?></small>
              </div>
            </div>

            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                  <i class="bi bi-house-door-fill"></i> Dashboard
                </a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between gap-2 <?php echo ($current_page == 'pengaduan.php') ? 'active' : ''; ?>" href="pengaduan.php">
                  <div><i class="bi bi-megaphone-fill me-2"></i> Pengaduan</div>
                  <?php if ($role_user == 'admin' && $count_notif > 0): ?>
                    <span class="badge bg-danger badge-notif" title="Antri & Proses"><?= $count_notif ?></span>
                  <?php endif; ?>
                </a>
              </li>

              <?php if ($role_user == 'admin') { ?>
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center gap-2 <?php echo ($current_page == 'pelapor.php') ? 'active' : ''; ?>" href="pelapor.php">
                    <i class="bi bi-people-fill"></i> Penduduk
                  </a>
                </li>
              <?php } ?> 

              <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between gap-2 <?php echo ($current_page == 'tanggapan.php') ? 'active' : ''; ?>" href="tanggapan.php">
                  <div><i class="bi bi-chat-left-dots-fill me-2"></i> Tanggapan</div>
                  <?php if ($role_user == 'pelapor' && $count_notif > 0): ?>
                    <span class="badge bg-info text-dark badge-notif">Baru</span>
                  <?php endif; ?>
                </a>
              </li>
              
              <?php if ($role_user == 'admin') { ?>
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center gap-2 <?php echo ($current_page == 'laporan.php') ? 'active' : ''; ?>" href="laporan.php">
                    <i class="bi bi-file-earmark-text-fill"></i> Laporan
                  </a>
                </li>
              <?php } ?> 
            </ul>

            <hr class="my-3" />
            <ul class="nav flex-column mb-auto">
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 text-danger" href="logout.php">
                  <i class="bi bi-box-arrow-right"></i> Sign out
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
