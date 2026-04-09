<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

$id = mysqli_real_escape_string($db, $_GET['id']);

$query = mysqli_query($db, "SELECT pengaduan.*, pelapor.nama, pelapor.nik 
                            FROM pengaduan 
                            JOIN pelapor ON pengaduan.id_pelapor = pelapor.id_pelapor 
                            WHERE pengaduan.id_pengaduan = '$id'");
$d = mysqli_fetch_assoc($query);

if (!$d) { echo "Data tidak ditemukan"; exit; }

$status = isset($d['status']) ? $d['status'] : 'pending';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - <?= $d['id_pengaduan'] ?></title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.5; 
            color: #2c3e50; 
            margin: 0; 
            padding: 0;
            background-color: #f4f4f4;
        }

        .paper {
            background: white;
            width: 210mm;
            margin: 20px auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: 297mm;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 4px double #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        /* Ukuran Logo Kabupaten Malang */
        .logo-instansi {
            width: 80px; /* Sesuaikan ukuran sesuai keinginan */
            height: auto;
            margin-right: 25px;
        }

        .instansi-info { text-align: left; flex-grow: 1; }
        .instansi-info h1 { margin: 0; font-size: 22px; text-transform: uppercase; color: #000; letter-spacing: 1px; }
        .instansi-info h2 { margin: 0; font-size: 18px; text-transform: uppercase; color: #000; margin-bottom: 5px; }
        .instansi-info p { margin: 2px 0; font-size: 12px; color: #333; }

        .judul-laporan { text-align: center; text-decoration: underline; margin-bottom: 30px; text-transform: uppercase; }

        /* Grid Info */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item { font-size: 13px; }
        .info-item strong { display: block; color: #7f8c8d; text-transform: uppercase; font-size: 11px; margin-bottom: 3px; }

        .section-title { 
            background: #f8f9fa; 
            padding: 8px 12px; 
            border-left: 4px solid #2980b9; 
            font-weight: bold; 
            margin: 20px 0 10px 0;
            font-size: 14px;
        }
        .content-box { 
            padding: 10px 12px; 
            font-size: 14px; 
            text-align: justify;
            white-space: pre-wrap; 
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .footer-sign {
            margin-top: 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            font-size: 13px;
        }
        .signature-box { text-align: center; }
        .space { height: 80px; }

        /* Print Settings */
        @media print {
            body { background: none; }
            .paper { box-shadow: none; margin: 0; width: 100%; padding: 10mm; }
            .no-print { display: none; }
            .content-box { border: none; padding-left: 0; }
        }

        .btn-group { text-align: center; margin-top: 20px; }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin: 0 5px;
        }
        .btn-print { background: #2ecc71; color: white; }
        .btn-close { background: #e74c3c; color: white; }
    </style>
</head>
<body>

    <div class="no-print btn-group">
        <button class="btn btn-print" onclick="window.print()">Cetak Laporan</button>
        <button class="btn btn-close" onclick="window.close()">Tutup</button>
    </div>

    <div class="paper">
        <div class="kop-surat">
            <img src="gambar.jpg" alt="Logo Kabupaten Malang" class="logo-instansi">
            <div class="instansi-info">
                <h1>PEMERINTAH KABUPATEN MALANG</h1>
                <h2>PENGADUAN MASYARAKAT ONLINE</h2>
                <p>Jl. Sunan Ampel 52 C Ngasem Ngajum Malang, Kode Pos 65164</p>
                <p>Email: pprqsmk@yahoo.com | Telp: 082139008567</p>
            </div>
        </div>

        <div class="judul-laporan">
            <h3>Laporan Rincian Pengaduan</h3>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <strong>Nomor Tiket</strong>
                #<?= $d['id_pengaduan'] ?>
            </div>
            <div class="info-item">
                <strong>Tanggal Laporan</strong>
                <?= date('d F Y', strtotime($d['tgl_pengaduan'])) ?>
            </div>
            <div class="info-item">
                <strong>Identitas Pelapor</strong>
                <?= $d['nama'] ?> (NIK: <?= $d['nik'] ?>)
            </div>
            <div class="info-item">
                <strong>Kategori Laporan</strong>
                <?= $d['kategori'] ?> / <?= $d['jenis_pengaduan'] ?>
            </div>
        </div>

        <div class="section-title">Isi Laporan / Pengaduan</div>
        <div class="content-box">
            <?= nl2br($d['isi_pengaduan']) ?>
        </div>

        <div class="section-title">Tanggapan Pejabat Berwenang</div>
        <div class="content-box" style="<?= empty($d['tanggapan']) ? 'font-style: italic; color: #95a5a6;' : '' ?>">
            <?= !empty($d['tanggapan']) ? nl2br($d['tanggapan']) : "Belum ada tanggapan resmi hingga dokumen ini dicetak." ?>
        </div>

        <div class="footer-sign">
            <div></div>
            <div class="signature-box">
                <p>Kab. Malang, <?= date('d/m/Y') ?></p>
                <p>Petugas Administrasi,</p>
                <div class="space"></div>
                <p><strong>( __________________________ )</strong></p>
                <p>NIP. .....................................</p>
            </div>
        </div>
    </div>

</body>
</html>