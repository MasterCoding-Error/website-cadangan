<!DOCTYPE html>
<html>
<head>
  <title>Pengaduan | Desa Web</title>
  <link href="datatables.css" rel="stylesheet">
  <link href="style-custom.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    .preview-link { cursor: pointer; transition: 0.2s; display: inline-block; }
    .preview-link:hover { opacity: 0.8; transform: scale(1.05); }
    .thumb-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    .modal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }
    main { padding-top: 20px; }
  </style>
</head>
<body>
  <?php
  session_start();
  if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit();
  }
  $id_user_session = $_SESSION['id'];
  include 'koneksi.php';
  include 'navbar.php';

  // --- LOGIKA UPDATE DATA + FILE ---
  if ($_POST && isset($_POST['proses']) && $_POST['proses'] == 'edit') {
    $id_pengaduan = mysqli_real_escape_string($db, $_POST['id_pengaduan']);
    $tgl_kejadian = mysqli_real_escape_string($db, $_POST['tgl_kejadian']);
    $jenis_pengaduan = mysqli_real_escape_string($db, $_POST['jenis_pengaduan']);
    $isi_pengaduan = mysqli_real_escape_string($db, $_POST['isi_pengaduan']);
    $kategori = mysqli_real_escape_string($db, $_POST['kategori']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $tanggapan = mysqli_real_escape_string($db, $_POST['tanggapan']);

    // Jika yang edit adalah admin, kita reset status 'dilihat_pelapor' jadi 0 
    // supaya notif muncul lagi di akun warga tersebut.
    $reset_notif = "";
    if ($_SESSION['role'] == 'admin') {
        $reset_notif = ", dilihat_pelapor = 0";
    }

    if (!empty($_FILES['foto']['name'])) {
      $nama_file = $_FILES['foto']['name'];
      $ukuran_file = $_FILES['foto']['size'];
      $max_size = 200 * 1024 * 1024; // 200MB

      if ($ukuran_file > $max_size) {
          echo "<script>Swal.fire('Gagal!', 'Ukuran file terlalu besar (Max 200MB)', 'error');</script>";
      } else {
          $new_name = time() . "_" . str_replace(' ', '+', $nama_file);
          move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $new_name);
          
          // Tambahkan $reset_notif di dalam query
          $query_update = "UPDATE pengaduan SET tgl_kejadian='$tgl_kejadian', jenis_pengaduan='$jenis_pengaduan', isi_pengaduan='$isi_pengaduan', kategori='$kategori', status='$status', tanggapan='$tanggapan', foto='$new_name' $reset_notif WHERE id_pengaduan='$id_pengaduan'";
          
          if (mysqli_query($db, $query_update)) {
            echo "<script>alert('Data Berhasil Diperbarui!'); window.location='pengaduan.php';</script>";
          }
      }
    } else {
      // Tambahkan $reset_notif di dalam query
      $query_update = "UPDATE pengaduan SET tgl_kejadian='$tgl_kejadian', jenis_pengaduan='$jenis_pengaduan', isi_pengaduan='$isi_pengaduan', kategori='$kategori', status='$status', tanggapan='$tanggapan' $reset_notif WHERE id_pengaduan='$id_pengaduan'";
      
      if (mysqli_query($db, $query_update)) {
        echo "<script>alert('Data Berhasil Diperbarui!'); window.location='pengaduan.php';</script>";
      }
    }
  }
  
  if (isset($_GET['proses']) && $_GET['proses'] == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($db, "DELETE FROM pengaduan WHERE id_pengaduan = '$id' ");
    echo "<script>window.location='pengaduan.php';</script>";
  }
  ?>

  <div class="container-fluid">
    <div class="row">
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="position: absolute; top: 30px; right: 0;">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Pengaduan</h1>
          <a href="pengaduan_tambah.php" class="btn btn-sm btn-outline-primary">Tambah Data</a>
        </div>

        <table class="table table-striped" id="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tgl Lapor</th>
              <th>Tgl Kejadian</th>
              <th>Media</th>
              <th>Jenis</th>
              <th>Kategori</th>
              <th>Isi</th>
              <th>Status</th>
              <th>Pelapor</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $q_str = ($_SESSION['role'] == 'admin') ? 
              "SELECT * FROM pengaduan WHERE status IN ('Antri', 'Proses') ORDER BY FIELD(status, 'Antri', 'Proses') ASC, id_pengaduan DESC" : 
              "SELECT * FROM pengaduan WHERE id_pelapor = (SELECT id_pelapor FROM pelapor WHERE id_user = '$id_user_session') AND status IN ('Antri', 'Proses') ORDER BY FIELD(status, 'Antri', 'Proses') ASC, id_pengaduan DESC";
            
            $res = mysqli_query($db, $q_str);
            $no = 1;
            while ($data = mysqli_fetch_assoc($res)) {
              $p_nama = mysqli_fetch_assoc(mysqli_query($db, "SELECT nama FROM pelapor WHERE id_pelapor = '".$data['id_pelapor']."'"))['nama'] ?? "Anonim";
              $file = $data['foto'];
              $ekstensi = strtolower(pathinfo($file, PATHINFO_EXTENSION));
              $file_url = "uploads/" . rawurlencode($file);
              $is_video = in_array($ekstensi, ['mp4', 'webm', 'ogg']);
              $file_exists = (!empty($file) && file_exists("uploads/".$file));
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $data['tgl_pengaduan'] ?></td>
                <td><?= $data['tgl_kejadian'] ?></td>
                <td>
                  <?php if ($file_exists): ?>
                    <img src="<?= $is_video ? 'video-icon.png' : $file_url ?>" class="thumb-img border">
                  <?php else: ?>
                    <span class="badge bg-danger">No File</span>
                  <?php endif; ?>
                </td>
                <td><?= $data['jenis_pengaduan'] ?></td>
                <td><?= $data['kategori'] ?></td>
                <td><?= mb_strimwidth($data['isi_pengaduan'], 0, 15, "...") ?></td>
                <td><span class="badge <?= ($data['status']=='Antri')?'bg-primary':'bg-warning text-dark' ?>"><?= $data['status'] ?></span></td>
                <td><?= $p_nama ?></td>
                <td>
                  <button type="button" class="btn btn-sm btn-outline-<?= ($data['status']=='Antri')?'primary':'info' ?>" data-bs-toggle="modal" data-bs-target="#edit-<?= $data['id_pengaduan'] ?>">
                    <i data-feather="<?= ($data['status']=='Antri')?'edit':'eye' ?>"></i>
                  </button>
                  <?php if($data['status'] == 'Antri'): ?>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus" data-id="<?= $data['id_pengaduan'] ?>">
                        <i data-feather="trash"></i>
                    </button>
                  <?php endif; ?>
                </td>
              </tr>

              <div class="modal fade" id="edit-<?= $data['id_pengaduan'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form class="form-edit-pengaduan" action="" method="POST" enctype="multipart/form-data">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit / Detail Pengaduan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id_pengaduan" value="<?= $data['id_pengaduan'] ?>">
                        
                        <?php 
                        $can_edit = ($_SESSION['role'] == 'pelapor' && $data['status'] == 'Antri'); 
                        $attr = $can_edit ? '' : 'disabled';
                        $readonly = $can_edit ? '' : 'readonly';
                        ?>

                        <div class="mb-3">
                          <label class="form-label">Tanggal Kejadian</label>
                          <input type="date" name="tgl_kejadian" class="form-control" value="<?= $data['tgl_kejadian'] ?>" <?= $readonly ?>>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Jenis Pengaduan</label>
                          <select name="jenis_pengaduan" class="form-select" <?= $attr ?>>
                            <?php
                            $list_jenis = ["Saran", "Kritik", "Keluhan", "Laporan"];
                            foreach ($list_jenis as $jns) {
                              $sel_jns = ($data['jenis_pengaduan'] == $jns) ? 'selected' : '';
                              echo "<option value=\"$jns\" $sel_jns>$jns</option>";
                            }
                            ?>
                          </select>
                          <?php if(!$can_edit): ?><input type="hidden" name="jenis_pengaduan" value="<?= $data['jenis_pengaduan'] ?>"><?php endif; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Kategori</label>
                          <select name="kategori" class="form-select" <?= $attr ?>>
                            <?php
                            $list_kategori = ["Kerusakan Jalan / Fasilitas Umum", "Laporan Pungutan Liar (Pungli)", "Kebersihan & Limbah", "Urusan Surat Menyurat", "Bantuan Sosial", "Keamanan Desa", "Kesehatan", "Sekolah & Pendidikan", "Kebersihan Lingkungan", "Masalah Tanah", "Usaha & Ekonomi Warga", "Perangkat Desa", "Lainnya.."];
                            foreach ($list_kategori as $kat) {
                              $selected = ($data['kategori'] == $kat) ? 'selected' : '';
                              echo "<option value=\"$kat\" $selected>$kat</option>";
                            }
                            ?>
                          </select>
                          <?php if(!$can_edit): ?><input type="hidden" name="kategori" value="<?= $data['kategori'] ?>"><?php endif; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Isi Laporan</label>
                          <textarea name="isi_pengaduan" class="form-control" rows="3" <?= $readonly ?>><?= $data['isi_pengaduan'] ?></textarea>
                        </div>

                        <div class="mb-3 text-center">
                          <label class="form-label d-block text-start">Media Lampiran:</label>
                          <?php if ($file_exists): ?>
                            <div class="mb-2 p-1 border rounded bg-light">
                              <?php if ($is_video): ?>
                                <video width="100%" controls><source src="<?= $file_url ?>"></video>
                              <?php else: ?>
                                <img src="<?= $file_url ?>" class="img-fluid rounded">
                              <?php endif; ?>
                            </div>
                          <?php endif; ?>
                          
                          <?php if($can_edit): ?>
                            <div class="mt-2 text-start">
                              <label class="small text-muted">Ganti File (Maks 200MB, jika tidak ada kosongi saja):</label>
                              <input type="file" name="foto" class="form-control form-control-sm input-foto-edit" accept="image/*,video/*">
                              <p class="text-danger small mt-1 italic" style="font-size: 10px;">* Jangan lampirkan jika file lebih dari 200MB.</p>
                            </div>
                          <?php endif; ?>
                        </div>

                        <?php if ($_SESSION['role'] == 'admin'): ?>
                          <hr>
                          <label class="form-label text-primary"><b>Panel Admin:</b></label>
                          <select name="status" class="form-select mb-2 radio-status" data-id="<?= $data['id_pengaduan'] ?>">
                            <option value="Antri" <?=($data['status']=='Antri')?'selected':''?>>Antri</option>
                            <option value="Proses" <?=($data['status']=='Proses')?'selected':''?>>Proses</option>
                            <option value="Selesai" <?=($data['status']=='Selesai')?'selected':''?>>Selesai</option>
                            <option value="Ditolak" <?=($data['status']=='Ditolak')?'selected':''?>>Ditolak</option>
                          </select>
                          <div id="wrapper-tanggapan-<?= $data['id_pengaduan'] ?>" style="display: <?= ($data['status'] == 'Selesai' || $data['status'] == 'Ditolak') ? 'block' : 'none' ?>;">
                            <textarea name="tanggapan" class="form-control" rows="2" placeholder="Tanggapan admin..."><?= $data['tanggapan'] ?></textarea>
                          </div>
                        <?php else: ?>
                          <input type="hidden" name="status" value="<?= $data['status'] ?>">
                          <input type="hidden" name="tanggapan" value="<?= $data['tanggapan'] ?>">
                          <?php if(!empty($data['tanggapan'])): ?>
                            <div class="alert alert-info small"><b>Tanggapan Admin:</b><br><?= $data['tanggapan'] ?></div>
                          <?php endif; ?>
                        <?php endif; ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <?php if($can_edit || $_SESSION['role'] == 'admin'): ?>
                          <button type="submit" name="proses" value="edit" class="btn btn-primary btn-simpan-edit">Simpan Perubahan</button>
                        <?php endif; ?>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php } ?>
          </tbody>
        </table>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script type="text/javascript" src="datatables.js"></script>
  <script>
  feather.replace();
  new DataTable('#table', { order: [] });

  document.querySelectorAll('.input-foto-edit').forEach(input => {
    input.addEventListener('change', function() {
      if (this.files.length > 0) {
        const fileSize = this.files[0].size;
        const maxSize = 200 * 1024 * 1024; 

        if (fileSize > maxSize) {
          const ukuranMB = (fileSize / (1024 * 1024)).toFixed(2);
          Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar Bro!',
            text: 'Ukuran file: ' + ukuranMB + ' MB. Ingat maksimal cuma 200 MB bro!!',
            confirmButtonColor: '#ef4444'
          });
          this.value = ""; 
        }
      }
    });
  });

  document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function() {
      const idHapus = this.getAttribute('data-id');

      Swal.fire({
        title: 'Yakin mau hapus?',
        text: "Data yang dihapus nggak bisa balik lagi lho!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' }
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '?proses=hapus&id=' + idHapus;
        }
      });
    });
  });

  document.querySelectorAll('.radio-status').forEach(select => {
    select.addEventListener('change', function() {
      const id = this.getAttribute('data-id');
      const wrapper = document.getElementById('wrapper-tanggapan-' + id);
      if(wrapper) wrapper.style.display = (this.value === 'Selesai' || this.value === 'Ditolak') ? 'block' : 'none';
    });
  });
</script>
</body>
</html>
