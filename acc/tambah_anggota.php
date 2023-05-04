<?php
session_start();
if ($_SESSION["login"] != true) {
  header("Location: login.php");
  exit();
}

if ($_SESSION["pertama_ganti_pw"] == 1) {
  header("Location: pertama_ganti_pw.php");
  exit();
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produkku - Anggota</title>
  <style>
    .nav-sa {
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
      <a class="navbar-brand nav-sa" href="../index.php">Produkku</a>
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
            <a class="nav-link" href="../produk/tambah_produk.php">
              <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="tambah_anggota.php">
              <i class="bi bi-fingerprint"></i> Tambah Anggota
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="profile.php">
              <i class="bi bi-person-circle"></i> Profile
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h4>Formulir Tambah Anggota</h4>
          </div>
          <div class="card-body">

            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
              require "../config/koneksi.php";

              $username = $_POST['username'];
              $password = $_POST['password'];

              $id = $_SESSION['id'];
              $error = false;


              $slc = mysqli_prepare($conn, "SELECT username FROM akun WHERE username = ?");
              mysqli_stmt_bind_param($slc, "s", $username);
              mysqli_stmt_execute($slc);
              $hasil = mysqli_stmt_get_result($slc);
              $row = mysqli_fetch_assoc($hasil);

              if ($row > 0) {
                echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> Username sudah ada yang pake!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                $error = true;
              }

              if (!$error) {
                if (strlen($password) <= 8) {
                  echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> Password minimal delapan karakter!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  $error = true;
                }
              }

              if (!$error) {
                $zero = 0;
                $stmt = mysqli_prepare($conn, "INSERT INTO akun(username, password) VALUES(?, ?)");
                mysqli_stmt_bind_param($stmt, "ss", $username, $password);
                mysqli_stmt_execute($stmt);
                mysqli_close($conn);
                echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> Berhasil menambah anggota!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              }
            }
            ?>

            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">

              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
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