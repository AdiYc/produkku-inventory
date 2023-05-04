<?php
session_start();
if ($_SESSION["login"] != true) {
  header("Location: ../login.php");
  exit();
}

if ($_SESSION["pertama_ganti_pw"] == 1) {
  header("Location: acc/pertama_ganti_pw.php");
  exit();
}

require "config/koneksi.php";
if (isset($_POST['submit'])) {
  $cari = $_POST['cari'];
  $sql = "SELECT * FROM produk WHERE nama_produk LIKE '%$cari%'";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die("Error: " . mysqli_error($conn));
  }
} else {
  $sql = "SELECT * FROM produk";
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    die("Error: " . mysqli_error($conn));
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produkku - Dashboard</title>
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
      <a class="navbar-brand nav-font" href="index.php">Produkku</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">
              <i class="bi bi-house-door"></i> Beranda
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="produk/tambah_produk.php">
              <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="acc/tambah_anggota.php">
              <i class="bi bi-fingerprint"></i> Tambah Anggota
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="acc/profile.php">
              <i class="bi bi-person-circle"></i> Profile
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto  mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <?php
  require "config/koneksi.php";

  if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%'";
  } else {
    $sql = "SELECT * FROM produk";
  }

  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die("Error: " . mysqli_error($conn));
  }

  echo '<div class="container mt-4">';
  echo '<div class="row justify-content-between align-items-center mb-3">';
  echo '<h3 class="col-md-6">Daftar Produk</h3>';
  echo '<div class="col-md-6 d-flex justify-content-end align-items-center">';
  echo '<button class="btn btn-primary me-3" onclick="window.location.href=\'produk/tambah_produk.php\'"><i class="bi bi-plus-circle"></i> Tambah Produk</button>';
  echo '<form method="GET">';
  echo '<div class="input-group">';
  echo '<input type="text" class="form-control" placeholder="Cari produk" name="search" value="' . (isset($_GET['search']) ? $_GET['search'] : '') . '">';
  echo '<button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>';
  echo '</div>';
  echo '</form>';
  echo '</div>';
  echo '</div>';
  echo '<hr>';
  echo '<table class="table">';
  echo '<thead>';
  echo '<tr>';
  echo '<th scope="col">#</th>';
  echo '<th scope="col">Gambar</th>';
  echo '<th scope="col">Nama Produk</th>';
  echo '<th scope="col">Deskripsi</th>';
  echo '<th scope="col">Harga</th>';
  echo '<th scope="col">Aksi</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  // looping untuk menampilkan data produk dalam tabel
  while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<th scope="row">' . $row["id"] . '</th>';
    echo '<td><img src="uploads/' . $row["gambar"] . '" alt="produk" width="150" height="150"></td>';
    echo '<td>' . $row["nama_produk"] . '</td>';
    echo '<td>' . $row["deskripsi"] . '</td>';
    echo '<td>Rp ' . number_format($row["harga"], 0, ',', '.') . '</td>';
    echo '<td>';
    echo '<td>';
    echo '<a href="produk/edit_produk.php?id=' . $row['id'] . '">';
    echo '<button class="btn btn-warning me-2"><i class="bi bi-pencil"></i></button>';
    echo '</a>';
    echo '<button class="btn btn-danger"  onclick="hapusProduk(' . $row['id'] . ')"><i class="bi bi-trash"></i></button>';
    echo '</td>';
    echo '</tr>';
  }

  echo '</tbody>';
  echo '</table>';
  echo '</div>';


  mysqli_close($conn);
  ?>

  <script>
    function hapusProduk(id) {
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus produk ini?");
      if (konfirmasi) {
        window.location.href = "produk/hapus_produk.php?id=" + id;
      }
    }
  </script>

  <script>
    function hapusProduk(id) {
      if (confirm("Anda yakin ingin menghapus produk ini?")) {
        window.location.href = 'produk/hapus_produk.php?id=' + id;
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>