<!DOCTYPE html>
<html>
<head>
  <title>Laporan</title>
  <link href="datatables.css" rel="stylesheet">
</head>
<body>
  <?php
  session_start();
  if ($_SESSION['status']!="login") {
    header("location:login.php?pesan=belum_login");
  }
  $id = $_SESSION['id'];
  include 'koneksi.php';
  include 'navbar.php';

  ?>
  <!-- KOLOM TAMBAH -->
  <div class="container-fluid">
    <div class="row">
     <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="position: absolute; top: 30px; right: 0;">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowarp align-itmes-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pelapor</h1>
        <div class="btn-toolbar mb<!DOCTYPE html>
<html>
<head>
  <title>Laporan</title>
  <link href="datatables.css" rel="stylesheet">
</head>
<body>
  <?php
  session_start();
  if ($_SESSION['status']!="login") {
    header("location:login.php?pesan=belum_login");
  }
  $id_session = $_SESSION['id']; // Mengubah nama agar tidak bentrok
  include 'koneksi.php';
  include 'navbar.php';
  ?>

  <div class="container-fluid">
    <div class="row">
     <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="position: absolute; top: 30px; right: 0;">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowarp align-itmes-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pelapor</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambah">Tambah Data</button>
            
            <div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="#" method="POST">
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
                       <label class="form-label">Nik</label>
                       <input name="nik" type="text" class="form-control" placeholder="Nik" required>
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
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

  <?php
  if ($_POST) {
    switch ($_POST['proses']) {
      case 'tambah':
        include 'koneksi.php';
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
          $query_tambah = mysqli_query($db, "INSERT INTO pelapor VALUES(NULL,'$nama','$alamat','$email','$nik','$jk','$no_hp','$username','$password','$id_new_user')");
          echo "<meta http-equiv='refresh' content='0; url=pelapor.php'>";
        }
      break;

      case 'edit':
        include 'koneksi.php';
        $id_target = $_POST['id_pelapor_hidden']; // ID yang dikirim dari form modal
        $nama = mysqli_real_escape_string($db,$_POST['nama']);
        $alamat = mysqli_real_escape_string($db,$_POST['alamat']);
        $email = mysqli_real_escape_string($db,$_POST['email']);
        $nik = mysqli_real_escape_string($db,$_POST['nik']);
        $jk = mysqli_real_escape_string($db,$_POST['jk']);
        $no_hp = mysqli_real_escape_string($db,$_POST['no_hp']);
        $username = mysqli_real_escape_string($db,$_POST['username']);
        $password = mysqli_real_escape_string($db,$_POST['password']);

        // Perbaikan Query: Menggunakan $id_target agar data yang berubah sesuai barisnya
        $query_edit = mysqli_query($db,"UPDATE pelapor SET nama='$nama', alamat='$alamat', email='$email', nik='$nik', jk='$jk', no_hp='$no_hp', username='$username', password='$password' WHERE id_pelapor = '$id_target'");
        if ($query_edit) {
          echo "<script>alert('Berhasil Edit')</script>";
          echo "<meta http-equiv='refresh' content='0; url=pelapor.php'>";
        }
      break;
    }
  }
  ?>

<table class="table table-striped" id="table">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>Email</th>
      <th>Nik</th>
      <th>Jenis Kelamin</th>
      <th>Nomor Hp</th>
      <th>Username</th>
      <th>Password</th>
      <th>Id User</th>
      <th>Aksi</th> 
    </tr>
  </thead>
  <tbody>
    <?php 
    include 'koneksi.php';
    $query_pelapor = mysqli_query($db,"SELECT * FROM pelapor");
    if (mysqli_num_rows($query_pelapor) == 0) {
      echo "<tr align='center'><td colspan='11'>Data Kosong</td></tr>";
    } else {
      $no = 1;
      while ($data = mysqli_fetch_assoc($query_pelapor)) {
        $query_user = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM user WHERE id_user = '$data[id_user]'"));
        echo "<tr> 
        <td>$no</td>
        <td>$data[nama]</td>
        <td>$data[alamat]</td>
        <td>$data[email]</td>
        <td>$data[nik]</td>
        <td>$data[jk]</td>
        <td>$data[no_hp]</td>
        <td>$data[username]</td>
        <td>$data[password]</td>
        <td>" . ($query_user['id_user'] ?? '-') . "</td>
        <td>
          <button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#edit-$data[id_pelapor]'><span data-feather='edit'></span></button>
          <button class='btn btn-sm btn-danger me-1'><span data-feather='trash'></span></button>
        </td>
        </tr>";
        $no++;
    ?>
      <div class="modal fade" id="edit-<?= $data['id_pelapor'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="#" method="POST">
                <input type="hidden" name="id_pelapor_hidden" value="<?= $data['id_pelapor'] ?>">
                
                <div class="mb-3">
                 <label class="form-label">Nama</label>
                 <input name="nama" type="text" class="form-control" required value="<?= $data['nama'] ?>">
               </div>
               <div class="mb-3">
                 <label class="form-label">Alamat</label>
                 <textarea class="form-control" name="alamat" required><?= $data['alamat'] ?></textarea>
               </div>
               <div class="mb-3">
                 <label class="form-label">Email</label>
                 <input name="email" type="text" class="form-control" required value="<?= $data['email'] ?>">
               </div>
               <div class="mb-3">
                 <label class="form-label">Nik</label>
                 <input name="nik" type="text" class="form-control" required value="<?= $data['nik'] ?>">
               </div>
               <div class="mb-3">
                 <label class="form-label">Jenis Kelamin</label>
                 <div class="form-check">
                  <input type="radio" name="jk" value="Laki-Laki" <?php if ($data['jk'] == "Laki-Laki") echo "checked"; ?>> Laki-Laki
                </div>
                <div class="form-check">
                  <input type="radio" name="jk" value="Perempuan" <?php if ($data['jk'] == "Perempuan") echo "checked"; ?>> Perempuan
                </div>
              </div>
              <div class="mb-3">
               <label class="form-label">Nomor Hp</label>
               <input name="no_hp" type="number" class="form-control" required value="<?= $data['no_hp'] ?>">
             </div>
             <div class="mb-3">
               <label class="form-label">Username</label>
               <input name="username" type="text" class="form-control" required value="<?= $data['username'] ?>">
             </div>
             <div class="mb-3">
               <label class="form-label">Password</label>
               <input name="password" type="password" class="form-control" required value="<?= $data['password'] ?>">
             </div>
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="proses" value="edit" class="btn btn-primary">Simpan</button>
           </form>
          </div>
        </div>
      </div>
    <?php } // AKHIR WHILE
    } ?>
  </tbody>
</table>
</main>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<!-- choose one -->
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
<script type="text/javascript" src="datatables.js"></script>
<script type="text/javascript">
  feather.replace();
  new DataTable('#table');
</script>
</body>
</html>-2 mb-md-0">
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambah">Tambah Data</button>
            <!-- Modal -->
            <div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="#" method="POST">
                      <div class="mb-3">
                       <label class="form-label">Nama</label>
                       <input name="nama" type="text" class="form-control" placeholder="Nama Lengkap" required>
                     </div>
                     <div class="mb-3">
                       <label class="form-label">Alamat</label>
                       <textarea class="form-control" name="alamat" required>
                       </textarea>
                     </div>
                     <div class="mb-3">
                       <label class="form-label">Email</label>
                       <input name="email" type="text" class="form-control" placeholder="Email" required>
                     </div>
                     <div class="mb-3">
                       <label class="form-label">Nik</label>
                       <input name="nik" type="text" class="form-control" placeholder="Nik" required>
                     </div>
                     <div class="mb-3">
                       <label class="form-label">Jenis Kelamin</label>
                       <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" checked value="Laki-Laki">
                        <label class="form-check-label">
                          Laki-Laki
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" checked value="Perempuan">
                        <label class="form-check-label">
                          Perempuan
                        </label>
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
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- KOLOM EDIT -->
  

  <?php
  if ($_POST) {
    switch ($_POST['proses']) {
      case 'tambah':
      include 'koneksi.php';

      $nama =  mysqli_real_escape_string($db,$_POST['nama']);
      $alamat =  mysqli_real_escape_string($db,$_POST['alamat']);
      $email =  mysqli_real_escape_string($db,$_POST['email']);
      $nik =  mysqli_real_escape_string($db,$_POST['nik']);
      $jk =  mysqli_real_escape_string($db,$_POST['jk']);
      $no_hp =  mysqli_real_escape_string($db,$_POST['no_hp']);
      $username =  mysqli_real_escape_string($db,$_POST['username']);
      $password =  mysqli_real_escape_string($db,$_POST['password']);

      $query_user = mysqli_query($db, "INSERT INTO user VALUES(NULL,'$username','$password','pelapor')");

      if ($query_user) {
        $id_pelapor = mysqli_fetch_assoc(mysqli_query($db,"SELECT id_user FROM user ORDER BY id_user DESC LIMIT 1"));
        $query_tambah = mysqli_query($db, "INSERT INTO pelapor VALUES(NULL,'$nama','$alamat','$email','$nik','$jk','$no_hp','$username','$password','$id_pelapor[id_user]')");
        if ($query_tambah) { 

          echo "<meta http-equiv='refresh' content='0
          url=pelapor.php?hal=barang'>";
        }else{
          echo "<script>alert('Anda Gagal Data')</script>";
          echo "<meta http-equiv='refresh' content='0
          url=#tambah'>";
        }
      } else {
        echo "<script>alert('Anda Gagal Data')</script>";
        echo "<meta http-equiv='refresh' content='0
        url=#tambah'>";
      }
      break;

      case 'edit':
      include 'koneksi.php';

      $nama =  mysqli_real_escape_string($db,$_POST['nama']);
      $alamat =  mysqli_real_escape_string($db,$_POST['alamat']);
      $email =  mysqli_real_escape_string($db,$_POST['email']);
      $nik =  mysqli_real_escape_string($db,$_POST['nik']);
      $jk =  mysqli_real_escape_string($db,$_POST['jk']);
      $no_hp =  mysqli_real_escape_string($db,$_POST['no_hp']);
      $username =  mysqli_real_escape_string($db,$_POST['username']);
      $password =  mysqli_real_escape_string($db,$_POST['password']);

      $query_edit = mysqli_query($db,"UPDATE pelapor SET nama = '$nama', alamat = '$alamat', email = '$email', nik = '$nik', jk = '$jk', no_hp = '$no_hp', username = '$username', password = '$password' WHERE id_pelapor = $id ");
      if ($query_edit) {
       echo "<script>alert('Anda Berhasil Edit')</script>";
       echo "<meta http-equiv='refresh' content='0
       url=pelapor.php?hal=pelapor'>";
     } else {
      echo "<script>alert('Anda Gagal Edit')</script>";
      echo "<meta http-equiv='refresh' content='0
      url=#edit?hal=pelapor&id=$id'>";
    }

    break;

    case 'hapus':
      # code...
    break;

    default:
        # code...
    break;
  }
}


?>
<table class="table table-striped" id="table">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>Email</th>
      <th>Nik</th>
      <th>Jenis Kelamin</th>
      <th>Nomor Hp</th>
      <th>Username</th>
      <th>Password</th>
      <th>Id User</th>
      <th>Aksi</th> 
    </tr>
  </thead>
  <tbody>
    <?php 
    include 'koneksi.php';
    $query_pelapor = mysqli_query($db,"SELECT * FROM pelapor");
    if (mysqli_num_rows($query_pelapor) == 0) {
      echo "<tr align='center'><td colspan='7'>Data Kosong</td></tr>";
    } else {
      $no = 1;
      while ($data = mysqli_fetch_assoc($query_pelapor)) {
        $query_user = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM user WHERE id_user = $data[id_user]"));
        echo "<tr> 
        <td>$no</td>
        <td>$data[nama]</td>
        <td>$data[alamat]</td>
        <td>$data[email]</td>
        <td>$data[nik]</td>
        <td>$data[jk]</td>
        <td>$data[no_hp]</td>
        <td>$data[username]</td>
        <td>$data[password]</td>
        <td>$query_user[id_user]</td>
        <td><button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#edit-$data[id_pelapor]'><span data-feather='edit'></span></button><button class='btn btn-sm btn-danger me-1'><span data-feather='trash'></span></button></td>
        </tr>";
        $no++;

      } ?>
      <div class="modal fade" id="edit-<?= $data['id_pelapor'] ?>" tabindex="-1"  aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="#" method="POST">
                <div class="mb-3">
                 <label class="form-label">Nama</label>
                 <input name="nama" type="text" class="form-control" placeholder="Nama Lengkap" required value="<?= $data['nama'] ?>">
               </div>
               <div class="mb-3">
                 <label class="form-label">Alamat</label>
                 <textarea class="form-control" name="alamat" required value="<?= $data['alamat'] ?>">
                 </textarea>
               </div>
               <div class="mb-3">
                 <label class="form-label">Email</label>
                 <input name="email" type="text" class="form-control" placeholder="Email" required value="<?= $data['email'] ?>">
               </div>
               <div class="mb-3">
                 <label class="form-label">Nik</label>
                 <input name="nik" type="text" class="form-control" placeholder="Nik" required value="<?= $data['nik'] ?>">
               </div>
               <div class="mb-3">
                 <label class="form-label">Jenis Kelamin</label>
                 <div class="form-check">
                  <input type="radio" name="jk" value="Laki-Laki" <?php if ($data['jk'] == "Laki-Laki") {echo "checked";}?> required>
                  <label class="form-check-label">
                    Laki-Laki
                  </label>
                </div>
                <div class="form-check">
                  <input type="radio" name="jk" value="Perempuan" <?php if ($data['jk'] == "Perempuan") {echo "checked";}?> required>
                  <label class="form-check-label">
                    Perempuan
                  </label>
                </div>
              </div>
              <div class="mb-3">
               <label class="form-label">Nomor Hp</label>
               <input name="no_hp" type="number" class="form-control" placeholder="Nomor Hp" required value="<?= $data['no_hp'] ?>">
             </div>
             <div class="mb-3">
               <label class="form-label">Username</label>
               <input name="username" type="text" class="form-control" placeholder="username" required value="<?= $data['username'] ?>">
             </div>
             <div class="mb-3">
               <label class="form-label">Password</label>
               <input name="password" type="password" class="form-control" placeholder="password" required value="<?= $data['password'] ?>">
             </div>
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="proses" value="edit" class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    <?php } ?>
  </tbody>
</table>
</main>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<!-- choose one -->
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
<script type="text/javascript" src="datatables.js"></script>
<script type="text/javascript">
  feather.replace();
  new DataTable('#table');
</script>
</body>
</html>