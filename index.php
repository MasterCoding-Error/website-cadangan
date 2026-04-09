<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Desa Web</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style type="text/css">
    :root {
        --primary-malang: #065f46;
        --secondary-malang: #0284c7;
        --accent-malang: #fbbf24;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f8fafc;
      overflow-x: hidden;
    }

    .dashboard-title {
      background: linear-gradient(to right, #065f46, #0284c7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 800;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border-radius: 2rem;
      border: 1px solid rgba(255, 255, 255, 0.4);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
    }

    .glass-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px -15px rgba(6, 95, 70, 0.15);
      border-color: var(--primary-malang);
    }

    .icon-box {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;      
      justify-content: center;
      border-radius: 1.25rem;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-in { animation: fadeIn 0.6s ease forwards; }

    .activity-item {
      border-left: 3px solid #e2e8f0;
      padding-left: 20px;
      position: relative;
    }
    .activity-item::before {
      content: '';
      position: absolute;
      left: -8px;
      top: 0;
      width: 13px;
      height: 13px;
      background: var(--secondary-malang);
      border-radius: 50%;
      border: 3px solid white;
    }

    @media (max-width: 768px) {
      main {
        position: relative !important; 
        top: 0 !important;
        width: 100% !important;
        padding-top: 20px !important;
      }
      
      .dashboard-title {
        font-size: 1.75rem !important;
      }

      .text-5xl {
        font-size: 2.5rem !important;
      }

      .glass-card {
        padding: 1.5rem !important;
        border-radius: 1.5rem !important;
      }
    }
  </style>
</head>
<body>

<?php
session_start();

// Proteksi login
if (($_SESSION['status'] ?? '') != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}

include 'koneksi.php';

$user_id    = $_SESSION['id'] ?? 0;
$user_level = $_SESSION['role'] ?? 'pelapor'; 

if ($user_level === 'admin') {
    $sql_antri    = "SELECT * FROM pengaduan WHERE status = 'Antri'";
    $sql_proses   = "SELECT * FROM pengaduan WHERE status = 'Proses'";
    $sql_selesai  = "SELECT * FROM pengaduan WHERE status = 'Selesai'";
    $sql_ditolak  = "SELECT * FROM pengaduan WHERE status = 'Ditolak'"; // Tambahan Ditolak
} else {
    $pelapor_id = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM pelapor WHERE id_user= '$user_id'"));
    $id_pel = $pelapor_id['id_pelapor'] ?? 0;
    $sql_antri    = "SELECT * FROM pengaduan WHERE status = 'Antri' AND id_pelapor = '$id_pel'";
    $sql_proses   = "SELECT * FROM pengaduan WHERE status = 'Proses' AND id_pelapor = '$id_pel'";
    $sql_selesai  = "SELECT * FROM pengaduan WHERE status = 'Selesai' AND id_pelapor = '$id_pel'";
    $sql_ditolak  = "SELECT * FROM pengaduan WHERE status = 'Ditolak' AND id_pelapor = '$id_pel'"; // Tambahan Ditolak
}

$total_penduduk = mysqli_num_rows(mysqli_query($db, "SELECT * FROM pelapor"));
$count_antri    = mysqli_num_rows(mysqli_query($db, $sql_antri));
$count_proses   = mysqli_num_rows(mysqli_query($db, $sql_proses));
$count_selesai  = mysqli_num_rows(mysqli_query($db, $sql_selesai));
$count_ditolak  = mysqli_num_rows(mysqli_query($db, $sql_ditolak)); // Hitung Ditolak

$total_surat_masuk = $count_antri + $count_proses + $count_selesai + $count_ditolak; // Update Total

include 'navbar.php'; 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="position: absolute; top: 50px; right: 0;">
  
  <div class="pt-8 pb-4 mb-6 border-b border-slate-200 px-4 animate-in">
    <h1 class="text-4xl dashboard-title text-center">Selamat Datang di Desa Web</h1>
    <p class="text-slate-500 text-center mt-2 font-medium">Sistem Informasi Pengaduan Masyarakat Kabupaten Malang</p>
  </div>

  <div class="grid grid-cols-1 <?= ($user_level === 'admin') ? 'md:grid-cols-3' : 'md:grid-cols-2' ?> gap-6 px-4 mt-8 animate-in" style="animation-delay: 0.2s;">
    
    <?php if ($user_level === 'admin'): ?>
    <div class="glass-card p-8">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-slate-500 font-bold uppercase tracking-wider text-xs">Total Data</p>
          <h5 class="text-xl font-bold text-slate-800 mt-1">Penduduk</h5>
          <h2 class="text-5xl font-extrabold text-emerald-700 mt-4">
            <?= number_format($total_penduduk, 0, ',', '.'); ?>
          </h2>
        </div>
        <div class="icon-box bg-emerald-100 text-emerald-600 shadow-inner">
          <i class="fas fa-users text-2xl"></i>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="glass-card p-8 cursor-pointer group" data-bs-toggle="modal" data-bs-target="#modalDetailMasuk">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-slate-500 font-bold uppercase tracking-wider text-xs">Statistik</p>
          <h5 class="text-xl font-bold text-slate-800 mt-1">Total Laporan</h5>
          <h2 class="text-5xl font-extrabold text-blue-700 mt-4">
            <?= $total_surat_masuk; ?>
          </h2>
          <p class="text-blue-500 text-sm mt-2 font-bold group-hover:underline">Klik rincian →</p>
        </div>
        <div class="icon-box bg-blue-100 text-blue-600 shadow-inner">
          <i class="fas fa-envelope-open-text text-2xl"></i>
        </div>
      </div>
    </div>

    <div class="glass-card p-8 cursor-pointer group bg-gradient-to-br from-amber-50 to-white border-amber-200" onclick="window.location.href='pengaduan_tambah.php';">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-amber-600 font-bold uppercase tracking-wider text-xs">Layanan</p>
          <h5 class="text-xl font-bold text-slate-800 mt-1">Buat Laporan</h5>
          <h2 class="text-2xl font-extrabold text-amber-600 mt-4">Tambah Data</h2>
          <p class="text-amber-500 text-sm mt-2 font-bold group-hover:translate-x-2 transition-transform">Klik untuk melapor →</p>
        </div>
        <div class="icon-box bg-amber-100 text-amber-600 shadow-inner">
          <i class="fas fa-plus-circle text-2xl"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 mt-10 animate-in" style="animation-delay: 0.4s;">
      <div class="glass-card p-6">
          <h5 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
              <i class="fas fa-chart-pie text-emerald-600"></i> Persentase Status Laporan
          </h5>
          <div class="relative" style="height: 250px;">
              <canvas id="statusChart"></canvas>
          </div>
      </div>

      <div class="glass-card p-6">
          <h5 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
              <i class="fas fa-bell text-blue-600 animate-pulse"></i> Aktivitas Sistem Terkini
          </h5>
          <div class="space-y-6 overflow-y-auto pr-2" style="max-height: 250px;">
              <div class="activity-item">
                  <p class="text-sm font-bold text-slate-700">Update Database Penduduk</p>
                  <p class="text-xs text-slate-500">Sistem baru saja memperbarui sinkronisasi data pelapor.</p>
                  <span class="text-[10px] text-emerald-600 font-bold uppercase">Baru Saja</span>
              </div>
              <div class="activity-item">
                  <p class="text-sm font-bold text-slate-700">Laporan Masuk Baru</p>
                  <p class="text-xs text-slate-500">Terdapat keluhan infrastruktur jalan dari warga RT 02.</p>
                  <span class="text-[10px] text-slate-400 font-bold uppercase">10 Menit yang lalu</span>
              </div>
              <div class="activity-item" style="border-left-color: transparent;">
                  <p class="text-sm font-bold text-slate-700">Pemeliharaan Server</p>
                  <p class="text-xs text-slate-500">Optimasi database selesai dilakukan secara otomatis.</p>
                  <span class="text-[10px] text-slate-400 font-bold uppercase">1 Jam yang lalu</span>
              </div>
          </div>
      </div>
  </div>
</main>

<div class="modal fade" id="modalDetailMasuk" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered px-4">
    <div class="modal-content border-0 shadow-2xl" style="border-radius: 2rem; overflow: hidden;">
      <div class="modal-header bg-emerald-900 text-white border-0 py-6 px-8">
        <h5 class="text-xl font-bold">Ringkasan Laporan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-6 md:p-8 bg-slate-50">
        <div class="space-y-4">
          <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 bg-slate-400 rounded-full"></span>
              <span class="font-bold text-slate-700">Antri</span>
            </div>
            <span class="bg-slate-100 text-slate-700 px-4 py-1 rounded-full font-bold"><?= $count_antri; ?></span>
          </div>
          <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
              <span class="font-bold text-slate-700">Proses</span>
            </div>
            <span class="bg-amber-100 text-amber-700 px-4 py-1 rounded-full font-bold"><?= $count_proses; ?></span>
          </div>
          <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
              <span class="font-bold text-slate-700">Selesai</span>
            </div>
            <span class="bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full font-bold"><?= $count_selesai; ?></span>
          </div>
          <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 bg-red-500 rounded-full"></span>
              <span class="font-bold text-slate-700">Ditolak</span>
            </div>
            <span class="bg-red-100 text-red-700 px-4 py-1 rounded-full font-bold"><?= $count_ditolak; ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const dataAntri = <?= $count_antri ?>;
const dataProses = <?= $count_proses ?>;
const dataSelesai = <?= $count_selesai ?>;
const dataDitolak = <?= $count_ditolak ?>; // Tambahan Variabel JS

// Registrasi plugin secara manual agar bisa digunakan
Chart.register(ChartDataLabels);

const ctx = document.getElementById('statusChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Antri', 'Proses', 'Selesai', 'Ditolak'], // Update Label
        datasets: [{
            data: [dataAntri, dataProses, dataSelesai, dataDitolak], // Update Data
            backgroundColor: ['#94a3b8', '#f59e0b', '#10b981', '#ef4444'], // Update Warna (Merah untuk Ditolak)
            borderWidth: 0,
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
                }
            },
            datalabels: {
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: (value, ctx) => {
                    return value > 0 ? value : ''; 
                }
            }
        },
        cutout: '70%'
    }
});
</script>

</body>
</html>