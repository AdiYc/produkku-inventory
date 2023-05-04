<?php
session_start();
if ($_SESSION["login"] != true) {
  header("Location: ../login.php");
  exit();
}


if ($_SESSION["pertama_ganti_pw"] == 1) {
  header("Location: ../acc/pertama_ganti_pw.php");
  exit();
}

require '../config/koneksi.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = "SELECT * FROM produk WHERE id='$id'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
    $data = mysqli_fetch_assoc($result);
  } else {

    header('location: ../index.php');
  }
} else {

  header('location: ../index.php');
}
if (isset($_POST['simpan'])) {
  $nama_produk = $_POST['nama_produk'];
  $deskripsi = $_POST['deskripsi'];
  $harga = $_POST['harga'];
  $nama_file = $_FILES['gambar']['name'];
  $ukuran_file = $_FILES['gambar']['size'];
  $tipe_file = $_FILES['gambar']['type'];
  $tmp_file = $_FILES['gambar']['tmp_name'];
  $folder = "../uploads/";
  $max_size = 1024 * 1024;
  $allowed_types = array("image/jpeg", "image/jpg", "image/png");

  if (isset($nama_file)) {
    $query = "UPDATE produk SET nama_produk='$nama_produk', deskripsi='$deskripsi', harga='$harga' WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
      header("Location: ../index.php");
    } else {
      echo "<script>alert('Terjadi kesalahan. Produk gagal diedit / diubah.');</script>";
    }
  }
  
  if ($ukuran_file > $max_size) {
    echo "<script>alert('Ukuran file terlalu besar. Maksimal 1MB.');</script>";
    exit;
  }
  if (!in_array($tipe_file, $allowed_types)) {
    echo "<script>alert('Tipe file tidak diizinkan. Hanya JPEG, PNG yang diizinkan.');</script>";
    exit;
  }
  $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
  $nama_file_baru = "produk_" . time() . ".$ext";
  $query = "";
  move_uploaded_file($tmp_file, $folder . $nama_file_baru);
  $query = "UPDATE produk SET nama_produk='$nama_produk', deskripsi='$deskripsi', harga='$harga', gambar='$nama_file_baru' WHERE id='$id'";
  $result = mysqli_query($conn, $query);
  if ($result) {
    header("Location: ../index.php");
  } else {
    echo "<script>alert('Terjadi kesalahan. Produk gagal diedit / diubah.');</script>";
  }
}
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produkku - Edit Produk</title>
  <style>
    .nav-font {
      font-family: 'Bruno Ace SC', cursive;
    }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bruno+Ace+SC&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-primary navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand nav-font" href="../index.php">Produkku</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="../index.php">
              <i class="bi bi-house-door"></i> Beranda
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="tambah_produk.php">
              <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../acc/tambah_anggota.php">
              <i class="bi bi-fingerprint"></i> Tambah Anggota
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../acc/profile.php">
              <i class="bi bi-person-circle"></i> Profile
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto  mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h4>Edit Produk</h4>
          </div>
          <div class="card-body">


            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

              <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo $data['nama_produk'] ?>" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php echo $data['deskripsi'] ?></textarea>
              </div>

              <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?php echo $data['harga'] ?>" required>
              </div>

              <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept=".jpg,.jpeg,.png">
                <div class="form-text">Maksimal ukuran file 1MB (jpeg, jpg, atau png)</div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary" name="simpan"><i class="bi bi-save"></i> Simpan</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.min.js" integrity="sha512-T8el6W90U6XjJUVp5oL+dFZzW5Q5otFFwJj0h/7zm/eeJQ2XO/ajlCbrNvchKjNohL7Fhr0wJNjKq3TtTSPVtQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>