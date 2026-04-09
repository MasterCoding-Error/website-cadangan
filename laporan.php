<!DOCTYPE html>
<html>
<head>
  <title>Laporan | Desa Web</title>
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
    .btn-action { padding: 8px 12px; }
  </style>
</head>
<body>
  <?php
  session_start();
  if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit();
  }
  if ($_SESSION['role'] == 'admin'){
    $id_user_session = $_SESSION['id'];
    include 'koneksi.php';
    include 'navbar.php';
    ?>

    <div class="container-fluid">
      <div class="row">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="position: absolute; top: 30px; right: 0;">

          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Data Laporan Pengaduan</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group me-2">
                <a href="laporan_print.php" id="btnCetak" target="_blank" class="btn btn-success">
                  <i data-feather="printer" style="width: 18px; height: 18px; vertical-align: middle;"></i> Cetak Laporan (<span id="textStatus">Semua</span>)
                </a>
              </div>
            </div>
          </div>

          <table class="table table-striped" id="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal Lapor</th>
                <th>Tanggal Kejadian</th>
                <th>Media</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Isi Pengaduan</th>
                <th>Status</th>
                <th>Pelapor</th>
                <th style="width: 80px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($_SESSION['role'] == 'admin') {
                $query_string = "SELECT * FROM pengaduan 
                ORDER BY FIELD(status, 'Antri', 'Proses', 'Selesai', 'Ditolak') ASC, id_pengaduan DESC";
              } else {
                $query_string = "SELECT * FROM pengaduan 
                WHERE id_pelapor = (SELECT id_pelapor FROM pelapor WHERE id_user = '$id_user_session') 
                ORDER BY FIELD(status, 'Antri', 'Proses', 'Selesai', 'Ditolak') ASC, id_pengaduan DESC";
              }

              $query_pengaduan = mysqli_query($db, $query_string);
              $no = 1;
              while ($data = mysqli_fetch_assoc($query_pengaduan)) {
                $data_pelapor = mysqli_fetch_assoc(mysqli_query($db, "SELECT nama FROM pelapor WHERE id_pelapor = '$data[id_pelapor]'"));
                $file = $data['foto'];
                $path_file = "uploads/" . $file;
                $file_url = "uploads/" . rawurlencode($file);
                $ekstensi = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $file_exists = (!empty($file) && file_exists($path_file)) ? true : false;
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= date('d/m/Y', strtotime($data['tgl_pengaduan'])) ?></td>
                  <td><?= date('d/m/Y', strtotime($data['tgl_kejadian'])) ?></td>
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
                      <td><?= mb_strimwidth($data['isi_pengaduan'], 0, 30, "...") ?></td>
                      <td>
                        <?php 
                        $bg = 'bg-secondary';
                        if($data['status'] == 'Antri') $bg = 'bg-primary';
                        if($data['status'] == 'Proses') $bg = 'bg-warning text-dark';
                        if($data['status'] == 'Selesai') $bg = 'bg-success';
                        if($data['status'] == 'Ditolak') $bg = 'bg-danger';
                        ?>
                        <span class="badge <?= $bg ?>"><?= $data['status'] ?></span>
                      </td>
                      <td><?= $data_pelapor['nama'] ?></td>
                      <td class="text-center">
                        <?php if ($data['status'] != 'Antri' && $data['status'] != 'Proses') : ?>
                          <a href="laporan_print.php?id=<?= $data['id_pengaduan'] ?>" target="_blank" class="btn btn-outline-success btn-action" title="Cetak Satuan">
                            <i data-feather="printer" style="width: 18px; height: 18px;"></i>
                          </a>
                          <?php else : ?>
                            <span class="text-muted small italic" style="font-size: 10px;">Menunggu...</span>
                          <?php endif; ?>
                        </td>
                      </tr>

                      <div class="modal fade" id="preview-<?= $data['id_pengaduan'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                          <div class="modal-content bg-dark border-0">
                            <div class="modal-header border-0">
                              <h6 class="modal-title text-white">Preview Media</h6>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0 text-center bg-black">
                              <?php if ($file_exists): ?>
                                <?php if (in_array($ekstensi, ['mp4', 'webm', 'ogg'])): ?>
                                  <video width="100%" controls><source src="<?= $file_url ?>" type="video/<?= $ekstensi ?>"></video>
                                    <?php else: ?>
                                      <img src="<?= $file_url ?>" class="img-fluid">
                                    <?php endif; ?>
                                  <?php endif; ?>
                                </div>
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

              <script type="text/javascript">
                feather.replace();

                const table = new DataTable('#table', { order: [] });

    // Filter Status (tetap mencakup semua pilihan)
    const filterHTML = `
    <div class="filter-wrapper">
    <label>Status: 
    <select id="filterStatus" class="form-select form-select-sm" style="display: inline-block; width: auto; margin-left: 5px;">
    <option value="">Semua</option>
    <option value="Antri">Antri</option>
    <option value="Proses">Proses</option>
    <option value="Selesai">Selesai</option>
    <option value="Ditolak">Ditolak</option>
    </select>
    </label>
    </div>
    `;

    const searchContainer = document.querySelector('.dt-search');
    if(searchContainer) {
      searchContainer.insertAdjacentHTML('afterbegin', filterHTML);
    }

    const btnCetak = document.getElementById('btnCetak');
    const textStatus = document.getElementById('textStatus');

    document.getElementById('filterStatus').addEventListener('change', function() {
      const valStatus = this.value;
      
      if(valStatus === "") {
        btnCetak.href = "laporan_print.php";
        textStatus.innerText = "Semua";
      } else {
        btnCetak.href = "laporan_print.php?status=" + valStatus;
        textStatus.innerText = valStatus;
      }

      const searchVal = valStatus ? '^' + valStatus + '$' : '';
      table.column(7).search(searchVal, true, false).draw();
    });
  </script>
</body>
</html>
<?php } else { ?>
  <div class="text-center my-3">
    <img src="gorila.jpg" class="rounded-2 shadow-sm border" alt="Gorila Default" style="width: 500px; height: 500px; object-fit: cover;">
    <h1 style="text-align: center;">HAHAHAHAHA MEMBER BODOHH</h1>
  </div>
  <?php } ?>