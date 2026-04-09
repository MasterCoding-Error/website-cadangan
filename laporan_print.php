<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

// Menangkap parameter ID (untuk cetak satu data) atau Status (untuk cetak per kategori)
$id = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($db, $_GET['status']) : '';

if (!empty($id)) {
    // Mode Cetak Tunggal (Detail)
    $query_string = "SELECT pengaduan.*, pelapor.nama, pelapor.nik 
                     FROM pengaduan 
                     JOIN pelapor ON pengaduan.id_pelapor = pelapor.id_pelapor 
                     WHERE pengaduan.id_pengaduan = '$id'";
    $mode = "single";
} else {
    // Mode Cetak Banyak (Rekap)
    $where_clause = "";
    if (!empty($status_filter)) {
        $where_clause = "WHERE pengaduan.status = '$status_filter'";
    }

    $query_string = "SELECT pengaduan.*, pelapor.nama, pelapor.nik 
                     FROM pengaduan 
                     JOIN pelapor ON pengaduan.id_pelapor = pelapor.id_pelapor 
                     $where_clause
                     ORDER BY tgl_pengaduan DESC";
    $mode = "all";
}

$result = mysqli_query($db, $query_string);

if ($mode == "single" && mysqli_num_rows($result) == 0) {
    echo "Data tidak ditemukan"; exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - <?= ($mode == 'single') ? 'Detail #'.$id : 'Semua Laporan' ?></title>
    <style>
        /* RESET & GLOBAL BOX SIZING */
        * { box-sizing: border-box; -moz-box-sizing: border-box; }
        
        @page { 
            size: A4; 
            margin: 0; 
        }

        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            line-height: 1.5; 
            color: #333; 
            margin: 0; 
            padding: 0;
            background-color: #f4f4f4;
        }

        .paper {
            background: white;
            width: 210mm;
            margin: 10px auto;
            padding: 20mm;
            min-height: 297mm;
            position: relative;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 4px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
            width: 100%;
        }
        
        .logo-instansi {
            width: 75px;
            height: auto;
            margin-right: 25px;
        }

        .instansi-info { text-align: left; flex-grow: 1; }
        .instansi-info h1 { margin: 0; font-size: 20px; text-transform: uppercase; color: #000; font-weight: bold; }
        .instansi-info h2 { margin: 0; font-size: 16px; text-transform: uppercase; color: #000; }
        .instansi-info p { margin: 2px 0; font-size: 11px; color: #333; }

        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h3 { text-decoration: underline; text-transform: uppercase; margin: 0; font-size: 16px; }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            font-size: 13px;
            table-layout: fixed;
        }
        
        th, td { 
            border: 1px solid #000; 
            padding: 10px; 
            vertical-align: top;
            word-wrap: break-word;
        }
        
        /* Mode Single */
        .table-single th { background-color: #f9f9f9; width: 30%; text-align: left; }
        .table-single td { width: 70%; }
        .row-header { background-color: #eee !important; font-weight: bold; text-align: center !important; text-transform: uppercase; }

        /* Mode All */
        .table-all th { background-color: #f2f2f2; text-align: center; font-weight: bold; }

        .footer-sign {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }
        .signature-box { text-align: center; width: 250px; font-size: 13px; }
        .space { height: 70px; }

        /* PRINT SETTINGS */
        @media print {
            body { background: none; margin: 0; padding: 0; }
            .paper { 
                box-shadow: none; 
                margin: 0; 
                width: 210mm;
                min-height: 297mm;
                padding: 15mm; 
            }
            .no-print { display: none; }
            
            .row-header, .table-all th, .table-single th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        .btn-group { text-align: center; padding: 20px; }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            color: white;
            font-family: sans-serif;
        }
        .btn-print { background: #2ecc71; }
        .btn-close { background: #e74c3c; margin-left: 10px; }
    </style>
</head>
<body>

    <div class="no-print btn-group">
        <button class="btn btn-print" onclick="window.print()">Cetak Laporan</button>
        <button class="btn btn-close" onclick="window.close()">Tutup</button>
    </div>

    <div class="paper">
        <div class="kop-surat">
            <img src="kabupaten.svg" alt="Logo" class="logo-instansi">
            <div class="instansi-info">
                <h1>PEMERINTAH KABUPATEN MALANG</h1>
                <h2>PENGADUAN MASYARAKAT ONLINE</h2>
                <p>Jl. Sunan Ampel 52 C Ngasem Ngajum Malang, Kode Pos 65164</p>
                <p>Email: pprqsmk@yahoo.com | Telp: 082139008567</p>
            </div>
        </div>

        <?php if ($mode == "single") : 
            $d = mysqli_fetch_assoc($result);
        ?>
            <div class="judul-laporan">
                <h3>Lembar Detail Pengaduan Masyakarat</h3>
            </div>

            <table class="table-single">
                <tr><th colspan="2" class="row-header">Data Identitas & Laporan</th></tr>
                <tr><th>ID Pengaduan</th><td><strong>#<?= $d['id_pengaduan'] ?></strong></td></tr>
                <tr><th>Tanggal Lapor</th><td><?= date('d F Y', strtotime($d['tgl_pengaduan'])) ?></td></tr>
                <tr><th>Nama Pelapor</th><td><?= $d['nama'] ?></td></tr>
                <tr><th>NIK</th><td><?= $d['nik'] ?></td></tr>
                <tr><th>Kategori / Jenis</th><td><?= $d['kategori'] ?> / <?= $d['jenis_pengaduan'] ?></td></tr>
                <tr><th colspan="2" class="row-header">Rincian Pengaduan</th></tr>
                <tr><td colspan="2" style="min-height: 120px;"><?= nl2br($d['isi_pengaduan']) ?></td></tr>
                <tr><th colspan="2" class="row-header">Tanggapan Petugas</th></tr>
                <tr><td colspan="2" style="min-height: 80px;"><?= !empty($d['tanggapan']) ? nl2br($d['tanggapan']) : "<em>Belum ada tanggapan.</em>" ?></td></tr>
                <tr><th>Status Akhir</th><td><strong><?= strtoupper($d['status']) ?></strong></td></tr>
            </table>

        <?php else : ?>
            <div class="judul-laporan">
                <h3>Rekapitulasi <?= !empty($status_filter) ? "Status: $status_filter" : "Seluruh" ?> Pengaduan</h3>
            </div>

            <table class="table-all">
                <thead>
                    <tr>
                        <th style="width: 40px">No</th>
                        <th style="width: 90px">Tanggal</th>
                        <th style="width: 140px">Pelapor</th>
                        <th style="width: 100px">Kategori</th>
                        <th>Isi Laporan</th>
                        <th style="width: 85px">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td style="text-align:center;"><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_pengaduan'])) ?></td>
                            <td><?= $row['nama'] ?><br><small>NIK: <?= $row['nik'] ?></small></td>
                            <td><?= $row['kategori'] ?></td>
                            <td><?= mb_strimwidth($row['isi_pengaduan'], 0, 100, "...") ?></td>
                            <td style="text-align:center;"><strong><?= strtoupper($row['status']) ?></strong></td>
                        </tr>
                        <?php } 
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data laporan.</td></tr>";
                    } ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="footer-sign">
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