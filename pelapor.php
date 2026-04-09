<!DOCTYPE html>
<html>
<head>
  <title>Penduduk | Desa Web</title>
  <link href="datatables.css" rel="stylesheet">
  <link href="style-custom.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <?php
  session_start();
  if ($_SESSION['status']!="login") {
    header("location:login.php?pesan=belum_login");
    exit();
  }
  if ($_SESSION['role'] == 'admin'){
    include 'koneksi.php';
    
    // --- LOGIKA HAPUS ---
    if (isset($_GET['proses']) && $_GET['proses'] == 'hapus') {
      $id_hapus = mysqli_real_escape_string($db, $_GET['id']);
      $cari = mysqli_query($db, "SELECT id_user FROM pelapor WHERE id_pelapor = '$id_hapus'");
      $data_pelapor = mysqli_fetch_assoc($cari);
      if ($data_pelapor) {
        $id_user_hapus = $data_pelapor['id_user'];
        mysqli_query($db, "DELETE FROM pelapor WHERE id_pelapor = '$id_hapus'");
        mysqli_query($db, "DELETE FROM user WHERE id_user = '$id_user_hapus'");
        echo "<script>alert('Data Berhasil Dihapus'); location.href='pelapor.php';</script>";
      }
    }

    // --- LOGIKA TAMBAH & EDIT (POST) ---
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proses'])) {
      if ($_POST['proses'] == 'tambah') {
        $nama = mysqli_real_escape_string($db,$_POST['nama']);
        $alamat = mysqli_real_escape_string($db,$_POST['alamat']);
        $email = mysqli_real_escape_string($db,$_POST['email']);
        $nik = mysqli_real_escape_string($db,$_POST['nik']);
        $jk = mysqli_real_escape_string($db,$_POST['jk']);
        $no_hp = mysqli_real_escape_string($db,$_POST['no_hp']);
        $username = mysqli_real_escape_string($db,$_POST['username']);
        $password = mysqli_real_escape_string($db,$_POST['password']);

        $query_user = mysqli_query($db, "INSERT INTO user VALUES(NULL,'$username','$password','pelapor')");
        if ($query_user) {
          $id_new_user = mysqli_insert_id($db);
                // PERBAIKAN DI SINI: Menyesuaikan jumlah kolom tabel pelapor (10 kolom)
          $query_tambah = mysqli_query($db, "INSERT INTO pelapor VALUES(NULL,'$nama','$alamat','$email','$nik','$jk','$no_hp','$username','$password','$id_new_user')");

          if($query_tambah){
           echo "<script>alert('Berhasil Tambah Data'); location.href='pelapor.php';</script>";
         } else {
           echo "Error: " . mysqli_error($db);
         }
       }
     }
     if ($_POST['proses'] == 'edit') {
      $id_pelapor = $_POST['id_pelapor_hidden'];
      $id_user = $_POST['id_user_hidden']; 
      $nama = mysqli_real_escape_string($db,$_POST['nama']);
      $username = mysqli_real_escape_string($db,$_POST['username']);
      $password = mysqli_real_escape_string($db,$_POST['password']);

      mysqli_query($db,"UPDATE user SET username = '$username', password = '$password' WHERE id_user = '$id_user'");
      mysqli_query($db,"UPDATE pelapor SET nama='$nama' WHERE id_pelapor = '$id_pelapor'");
      echo "<script>alert('Berhasil Edit'); location.href='pelapor.php';</script>";
    }
  }

  include 'navbar.php';
  ?>

  <div class="container-fluid">
    <div class="row">
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3" style="position: absolute; top: 30px; right: 0;">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowarp align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Penduduk</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambah">Tambah Data</button>
              <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#importFile">Import File</button>
            </div>
          </div>
        </div>

        <div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="#" method="POST">
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input name="nama" type="text" class="form-control" placeholder="Nama Lengkap" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" required></textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="text" class="form-control" placeholder="Email" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">NIK</label>
                    <input name="nik" type="number" class="form-control" placeholder="NIK" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jk" value="Laki-Laki" checked>
                      <label class="form-check-label">Laki-Laki</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jk" value="Perempuan">
                      <label class="form-check-label">Perempuan</label>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Nomor Hp</label>
                    <input name="no_hp" type="number" class="form-control" placeholder="Nomor Hp" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">username</label>
                    <input name="username" type="text" class="form-control" placeholder="username" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">password</label>
                    <input name="password" type="password" class="form-control" placeholder="password" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" name="proses" value="tambah" class="btn btn-primary">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-striped" id="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>NIK</th>
                <th>Jenis Kelamin</th>
                <th>Nomor Hp</th>
                <th>Username</th>
                <th>Password</th>
                <th>Aksi</th> 
              </tr>
            </thead>
            <tbody>
              <?php 
              $query_pelapor = mysqli_query($db,"SELECT * FROM pelapor");
              if (mysqli_num_rows($query_pelapor) == 0) {
                echo "<tr align='center'><td colspan='10'>Data Kosong</td></tr>";
              } else {
                $no = 1;
                while ($data = mysqli_fetch_assoc($query_pelapor)) {
                  $q_u = mysqli_query($db,"SELECT * FROM user WHERE id_user = '$data[id_user]'");
                  $query_user = mysqli_fetch_assoc($q_u);
                  ?>
                  <tr> 
                    <td><?= $no++ ?></td>
                    <td><?= $data['nama'] ?></td>
                    <td><?= $data['alamat'] ?></td>
                    <td><?= $data['email'] ?></td>
                    <td><?= $data['nik'] ?></td>
                    <td><?= $data['jk'] ?></td>
                    <td><?= $data['no_hp'] ?></td>
                    <td><?= $query_user['username'] ?? '-' ?></td>
                    <td><?= $query_user['password'] ?? '-' ?></td>
                    <td>
                      <button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#edit-<?= $data['id_pelapor'] ?>'><span data-feather='edit'></span></button>
                      <button type='button' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#hapus-<?= $data['id_pelapor'] ?>'><span data-feather='trash'></span></button>
                    </td>
                  </tr>

                  <div class="modal fade" id="edit-<?= $data['id_pelapor'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit Data</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="#" method="POST">
                          <div class="modal-body">
                            <input type="hidden" name="id_pelapor_hidden" value="<?= $data['id_pelapor'] ?>">
                            <input type="hidden" name="id_user_hidden" value="<?= $data['id_user'] ?>">
                            <div class="mb-3">
                              <label class="form-label">Nama</label>
                              <input name="nama" type="text" class="form-control" required value="<?= $data['nama'] ?>">
                            </div>
                            <div class="mb-3">
                              <label class="form-label font-bold">Alamat</label>
                              <textarea class="form-control" name="alamat" required><?= $data['alamat'] ?></textarea>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input name="email" type="text" class="form-control" required value="<?= $data['email'] ?>">
                            </div>
                            <div class="mb-3">
                              <label class="form-label">NIK</label>
                              <input name="nik" type="number" class="form-control" required value="<?= $data['nik'] ?>">
                            </div>
                            <div class="mb-3">
                              <label class="form-label font-bold">Jenis Kelamin</label>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="jk" value="Laki-Laki" 
                                <?php echo ($data['jk'] == 'Laki-Laki') ? 'checked' : ''; ?>>
                                <label class="form-check-label">Laki-Laki</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="jk" value="Perempuan" 
                                <?php echo ($data['jk'] == 'Perempuan') ? 'checked' : ''; ?>>
                                <label class="form-check-label">Perempuan</label>
                              </div>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Nomor Hp</label>
                              <input name="no_hp" type="number" class="form-control" required value="<?= $data['no_hp'] ?>">
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Username</label>
                              <input name="username" type="text" class="form-control" required value="<?= $query_user['username'] ?>">
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Password</label>
                              <input name="password" type="text" class="form-control" required value="<?= $query_user['password'] ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="proses" value="edit" class="btn btn-primary">Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <div class="modal fade" id="hapus-<?= $data['id_pelapor'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Konfirmasi Hapus</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <p>Apakah Anda yakin ingin menghapus data <strong><?= $data['nama'] ?></strong>?</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <a href="pelapor.php?proses=hapus&id=<?= $data['id_pelapor'] ?>" class="btn btn-danger">Iya, Hapus</a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } 
              } ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script type="text/javascript" src="datatables.js"></script>
  <script type="text/javascript">
    feather.replace();
    new DataTable('#table');
  </script>
</body>
</html>
<?php } else { ?>
  <div class="text-center my-3">
    <img src="gorila.jpg" class="rounded-2 shadow-sm border" alt="Gorila Default" style="width: 500px; height: 500px; object-fit: cover;">
    <h1 style="text-align: center;">HAHAHAHAHA MEMBER BODOHH</h1>
  </div>
  <?php } ?>