<?php
include 'koneksi.php';

/**
 * MINI LIBRARY SimpleXLSX (Terintegrasi)
 */
class SimpleXLSX {
    private $nodes;
    private $ss = [];
    public function __construct($file) {
        if (!class_exists('ZipArchive')) {
            die("<div style='color:red; font-family:sans-serif;'>
                <h3>Error: ZipArchive tidak aktif!</h3>
                <p>Buka XAMPP Control Panel -> Config Apache -> php.ini -> cari <b>;extension=zip</b> -> hapus tanda (;) -> Restart Apache.</p>
                </div>");
        }
        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            if ($xml = $zip->getFromName('xl/sharedStrings.xml')) {
                $sxml = simplexml_load_string($xml);
                foreach ($sxml->si as $val) $this->ss[] = (string)$val->t;
            }
            if ($xml = $zip->getFromName('xl/worksheets/sheet1.xml')) {
                $this->nodes = simplexml_load_string($xml);
            }
            $zip->close();
        }
    }
    public static function parse($file) { return new self($file); }
    public function rows() {
        $rows = [];
        if (!$this->nodes) return $rows;
        foreach ($this->nodes->sheetData->row as $row) {
            $r = [];
            foreach ($row->c as $c) {
                $v = (string)$c->v;
                $r[] = (isset($c['t']) && $c['t'] == 's') ? ($this->ss[$v] ?? '') : $v;
            }
            $rows[] = $r;
        }
        return $rows;
    }
}

// --- LOGIKA PROSES DATA ---
if (isset($_FILES['file_upload'])) {
    $file_tmp = $_FILES['file_upload']['tmp_name'];
    $ext = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);

    if ($ext == 'xlsx') {
        $xlsx = SimpleXLSX::parse($file_tmp);
        $rows = $xlsx->rows();
        $berhasil = 0;

        foreach ($rows as $index => $data) {
            if ($index == 0) continue; 
            if (empty($data[1])) continue; 

            $nama     = mysqli_real_escape_string($db, $data[1]);
            $alamat   = mysqli_real_escape_string($db, $data[2]);
            $email    = mysqli_real_escape_string($db, $data[3]);
            $nik      = mysqli_real_escape_string($db, $data[4]);
            $jk       = mysqli_real_escape_string($db, $data[5]);
            $no_hp    = mysqli_real_escape_string($db, $data[6]);
            $username = mysqli_real_escape_string($db, $data[7]);
            $password = mysqli_real_escape_string($db, $data[8]);

            // PERBAIKAN DI SINI: Menghapus nama kolom 'role' dan menggunakan format VALUES sesuai kode awal kamu
            $q_user = mysqli_query($db, "INSERT INTO user VALUES (NULL, '$username', '$password', 'pelapor')");
            
            if ($q_user) {
                $id_user = mysqli_insert_id($db);
                // Simpan ke tabel Pelapor
                $q_pelapor = mysqli_query($db, "INSERT INTO pelapor VALUES (NULL, '$nama', '$alamat', '$email', '$nik', '$jk', '$no_hp', '$username', '$password', '$id_user')");
                if ($q_pelapor) $berhasil++;
            }
        }
        echo "<script>alert('Sukses! $berhasil data berhasil masuk.'); window.location='pelapor.php';</script>";
    } else {
        echo "<script>alert('Error: File harus format Excel (.xlsx)'); window.location='pelapor.php';</script>";
    }
}
?>