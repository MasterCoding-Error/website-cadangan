<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaduan | Desa Web</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="datatables.css" rel="stylesheet">
  <link href="style-custom.css" rel="stylesheet">
  <style>
    body { background-color: #f8fafc; overflow-x: hidden; }
    .form-input-custom {
      background-color: #ffffff;
      border: 1px solid #cbd5e1;
      transition: all 0.2s ease;
      font-size: 1rem;
    }
    .form-input-custom:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
      outline: none;
    }
    .admin-input-style {
      border: 2px solid #4f46e5 !important;
      background-color: #f0f7ff !important;
    }
  </style>
</head>
<body>
  <?php
  session_start();
  if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
  }
  date_default_timezone_set('Asia/Jakarta');
  $tanggal_sekarang = date('Y-m-d');
  $id_user = $_SESSION['id'];
  include 'koneksi.php';
  
  $level_user = isset($_SESSION['role']) ? $_SESSION['role'] : ''; 
  
  $query_pelapor = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM pelapor WHERE id_user = '$id_user'"));
  include 'navbar.php'; 
  ?>

  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="position: absolute; top: 50px; right: 0;">
    <div class="w-full mb-8">
      <div class="flex justify-between items-center border-b-2 border-slate-200 pb-6">
        <div>
          <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Buat Laporan Baru</h1>
          <p class="text-slate-500 text-base mt-1">Lengkapi form di bawah untuk mengirim pengaduan.</p>
        </div>
      </div>
    </div>

    <div class="w-full">
      <form id="formPengaduan" action="#" method="POST" enctype="multipart/form-data" class="space-y-8">

        <div class="space-y-3">
          <label class="block text-sm font-bold text-slate-700">Nama Pelapor</label>
          <?php if ($level_user == 'admin') : ?>
            <input type="text" name="nama_manual" placeholder="Ketik nama pelapor secara manual..." required 
            class="form-input-custom admin-input-style w-full px-5 py-4 rounded-2xl shadow-sm" list="data_warga">
            <datalist id="data_warga">
              <?php
              $res = mysqli_query($db, "SELECT nama FROM pelapor");
              while($row = mysqli_fetch_assoc($res)) { echo "<option value='".$row['nama']."'>"; }
              ?>
            </datalist>
            <p class="text-xs text-blue-600 font-bold italic">Mode Admin: Silahkan ketik nama pelapor.</p>
          <?php else : ?>
            <input type="text" value="<?php echo isset($query_pelapor['nama']) ? $query_pelapor['nama'] : 'Nama Pelapor'; ?>" 
            class="form-input-custom w-full px-5 py-4 rounded-2xl shadow-sm bg-slate-100 cursor-not-allowed" readonly>
            <input type="hidden" name="id_pelapor_otomatis" value="<?php echo $query_pelapor['id_pelapor']; ?>">
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="space-y-3">
            <label class="block text-sm font-bold text-slate-700">Tanggal Pengaduan</label>
            <input type="date" name="tgl_pengaduan" value="<?php echo $tanggal_sekarang; ?>" required class="form-input-custom w-full px-5 py-4 rounded-2xl shadow-sm" readonly>
          </div>
          <div class="space-y-3">
            <label class="block text-sm font-bold text-slate-700">Tanggal Kejadian</label>
            <input type="date" name="tgl_kejadian" max="<?php echo $tanggal_sekarang; ?>" required class="form-input-custom w-full px-5 py-4 rounded-2xl shadow-sm">
          </div>
        </div>

        <div class="space-y-3">
          <label class="block text-sm font-bold text-slate-700">Jenis Pengaduan</label>
          <select name="jenis_pengaduan" required class="form-input-custom w-full px-5 py-4 rounded-2xl shadow-sm appearance-none cursor-pointer">
            <option value="" disabled selected>Pilih Jenis...</option>
            <option value="Saran">Saran</option>
            <option value="Kritik">Kritik</option>
            <option value="Keluhan">Keluhan</option>
            <option value="Laporan">Laporan</option>
          </select>
        </div>

        <div class="space-y-3">
          <label class="block text-sm font-bold text-slate-700">Kategori Spesifik</label>
          <select name="kategori" required class="form-input-custom w-full px-5 py-4 rounded-2xl shadow-sm cursor-pointer">
            <option value="" disabled selected>Pilih Kategori...</option>
            <option value="Kerusakan Jalan / Fasilitas Umum">Kerusakan Jalan / Fasilitas Umum</option>
            <option value="Laporan Pungutan Liar (Pungli)">Laporan Pungutan Liar (Pungli)</option>
            <option value="Kebersihan & Limbah">Kebersihan & Limbah</option>
            <option value="Urusan Surat Menyurat">Urusan Surat Menyurat</option>
            <option value="Bantuan Sosial">Bantuan Sosial</option>
            <option value="Keamanan Desa">Keamanan Desa</option>
            <option value="Kesehatan">Kesehatan</option>
            <option value="Sekolah & Pendidikan">Sekolah & Pendidikan</option>
            <option value="Kebersihan Lingkungan">Kebersihan Lingkungan</option>
            <option value="Masalah Tanah">Masalah Tanah</option>
            <option value="Usaha & Ekonomi Warga">Usaha & Ekonomi Warga</option>
            <option value="Perangkat Desa">Perangkat Desa</option>
            <option value="Lainnya..">Lainnya..</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label block text-sm font-bold text-slate-700">Lampirkan Foto Atau Video Jika Ada</label>
          <input id="inputFoto" class="form-control form-input-custom w-full px-5 py-3 rounded-2xl" type="file" name="foto" accept="image/*,video/*">
          <p class="text-[11px] text-red-500 mt-2 font-medium italic">
            * Maksimal ukuran file foto atau video adalah 200MB.
          </p>
        </div>

        <div class="space-y-3">
          <label class="block text-sm font-bold text-slate-700">Detail Isi Laporan</label>
          <textarea name="isi_pengaduan" rows="6" placeholder="Tuliskan laporan Anda secara jelas di sini..." required class="form-input-custom w-full px-5 py-5 rounded-2xl shadow-sm resize-none"></textarea>
          <div class="flex justify-between items-center text-xs text-slate-400 font-medium px-2">
            <span><i class="fas fa-lock mr-1 text-green-500"></i> Laporan Anda bersifat rahasia</span>
          </div>
        </div>

        <div class="flex justify-end pt-6">
          <button type="submit" name="kirim" class="w-full md:w-auto bg-slate-900 hover:bg-indigo-600 text-white font-bold py-4 px-12 rounded-2xl shadow-xl transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
            Kirim Laporan
            <i class="fas fa-paper-plane text-sm"></i>
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    document.getElementById('formPengaduan').addEventListener('submit', function(e) {
      const fileInput = document.getElementById('inputFoto');
      if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size; // Ukuran dalam Bytes
        const maxSize = 200 * 1024 * 1024; // 200MB

        if (fileSize > maxSize) {
          e.preventDefault(); // STOP PROSES POST!
          
          const ukuranMB = (fileSize / (1024 * 1024)).toFixed(2);
          
          Swal.fire({
            icon: 'error',
            title: 'Waduh, Kegedean!',
            text: 'File kamu ' + ukuranMB + ' MB. Maksimal cuma boleh 200 MB ya!',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Oke, Saya Ganti'
          });
        }
      }
    });
  </script>

  <?php 
  if (isset($_POST['kirim'])) {
    $tgl_pengaduan = mysqli_real_escape_string($db, $_POST['tgl_pengaduan']);
    $tgl_kejadian = mysqli_real_escape_string($db, $_POST['tgl_kejadian']);
    $jenis_pengaduan = mysqli_real_escape_string($db, $_POST['jenis_pengaduan']);
    $isi_pengaduan = mysqli_real_escape_string($db, $_POST['isi_pengaduan']);
    $kategori = mysqli_real_escape_string($db, $_POST['kategori']);
    
    // Validasi Tanggal Kejadian
    if ($tgl_kejadian > $tanggal_sekarang) {
        echo "<script>
                Swal.fire({
                  icon: 'warning',
                  title: 'Tanggal Tidak Valid',
                  text: 'Tanggal kejadian tidak boleh melebihi hari ini!',
                  confirmButtonColor: '#4f46e5'
                }).then(() => { window.history.back(); });
              </script>";
        exit;
    }

    // Proses Identitas Pelapor
    if ($level_user == 'admin') {
      $nama_input = mysqli_real_escape_string($db, $_POST['nama_manual']);
      $cek_warga = mysqli_query($db, "SELECT id_pelapor FROM pelapor WHERE nama = '$nama_input'");
      if (mysqli_num_rows($cek_warga) > 0) {
        $dw = mysqli_fetch_assoc($cek_warga);
        $id_pelapor = $dw['id_pelapor'];
      } else {
        mysqli_query($db, "INSERT INTO pelapor (nama, nik, id_user) VALUES ('$nama_input', '-', NULL)");
        $id_pelapor = mysqli_insert_id($db);
      }
    } else {
      $id_pelapor = mysqli_real_escape_string($db, $_POST['id_pelapor_otomatis']);
    }

    // Proses File Upload (Validasi Sisi Server)
    $nama_file = $_FILES['foto']['name'];
    $tmp_file = $_FILES['foto']['tmp_name'];
    $ukuran_file = $_FILES['foto']['size']; 
    $foto_untuk_db = ""; 

    if (!empty($nama_file)) {
      $max_size = 200 * 1024 * 1024; // 200MB

      if ($ukuran_file > $max_size) {
          echo "<script>
                  Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file melebihi batas maksimal 200MB!',
                    confirmButtonColor: '#ef4444'
                  }).then(() => { window.history.back(); });
                </script>";
          exit;
      }

      $nama_file_bersih = str_replace(' ', '+', $nama_file);
      move_uploaded_file($tmp_file, "uploads/" . $nama_file_bersih);
      $foto_untuk_db = $nama_file_bersih;
    }

    // Insert ke Database
    $query_tambah = mysqli_query($db, "INSERT INTO pengaduan (id_pengaduan, tgl_pengaduan, tgl_kejadian, jenis_pengaduan, foto, isi_pengaduan, status, id_pelapor, kategori) 
      VALUES (NULL, '$tgl_pengaduan', '$tgl_kejadian', '$jenis_pengaduan', '$foto_untuk_db', '$isi_pengaduan', 'Antri', '$id_pelapor', '$kategori')");

    if ($query_tambah) {
      echo "<script>
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Laporan Anda telah terkirim ke sistem.',
                confirmButtonColor: '#10b981'
              }).then(() => { window.location='pengaduan.php'; });
            </script>";
    } else {
      $error_db = mysqli_error($db);
      echo "<script>
              Swal.fire({
                icon: 'error',
                title: 'Gagal Mengirim',
                text: 'Terjadi kesalahan: $error_db',
                confirmButtonColor: '#ef4444'
              });
            </script>";
    }
  }
  ?>
</body>
</html>