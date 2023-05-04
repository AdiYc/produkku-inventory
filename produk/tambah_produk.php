<?php
session_start();
if ($_SESSION["login"] != true) {
  header("Location: login.php");
  exit();
}


if ($_SESSION["pertama_ganti_pw"] == 1) {
  header("Location: ../acc/pertama_ganti_pw.php");
  exit();
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produkku - Produk</title>
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
            <a class="nav-link active" href="tambah_produk.php">
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
            <h4>Formulir Tambah Produk</h4>
          </div>
          <div class="card-body">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
              require "../config/koneksi.php";
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
              move_uploaded_file($tmp_file, $folder . $nama_file_baru);
              $query = "INSERT INTO produk (nama_produk, deskripsi, harga, gambar) VALUES ('$nama_produk', '$deskripsi', $harga, '$nama_file_baru')";
              $result = mysqli_query($conn, $query);
              if ($result) {
                echo "<script>alert('Produk berhasil ditambahkan.');</script>";
              } else {
                echo "<script>alert('Terjadi kesalahan. Produk gagal ditambahkan.');</script>";
              }
            }
            ?>
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

              <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
              </div>

              <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
              </div>

              <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
              </div>

              <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept=".jpg,.jpeg,.png" required>
                <div class="form-text">Maksimal ukuran file 1MB (jpeg, jpg, atau png)</div>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>