<?php
session_start();
if (isset($_SESSION['login'])) {
  header("Location: index.php");
  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produkku - Login Dashboard</title>
  <link rel="stylesheet" href="assets/css/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form class="form-login" autocomplete="off" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
          <div class="card">
            <div class="card-header">
              <i class="bi bi-person-circle"></i> Login Produkku
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
              require "config/koneksi.php";

              $username_akun = $_POST['username'];
              $password_akun = $_POST['password'];
              $error = false;

              if ($username_akun == "" || $password_akun == "") {
                echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> Sebagian data ada yang kosong!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                $error = true;
              }

              if (!$error) {
                $username_akun = mysqli_real_escape_string($conn, $username_akun);
                $stmt = mysqli_prepare($conn, "SELECT * FROM akun WHERE username = ? AND password = ?");
                mysqli_stmt_bind_param($stmt, "ss", $username_akun, $password_akun);
                mysqli_stmt_execute($stmt);

                $hasil = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($hasil) > 0) {
                  session_start();
                  $row = mysqli_fetch_assoc($hasil);
                  $_SESSION['id'] = $row['id'];
                  $_SESSION['username'] = $row['username'];
                  $_SESSION['pertama_ganti_pw'] = $row['pertama_ganti_pw'];
                  $_SESSION['login'] = true;
                  mysqli_close($conn);

                  if ($_SESSION['pertama_ganti_pw'] == 1) {
                    header("Location: acc/pertama_ganti_pw.php");
                  } else {
                    header("Location: index.php");
                  }
                } else {
                  echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i> Username atau password salah!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                }
              }
            }
            ?>
            <div class="card-body">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="username" class="form-control" name="username" placeholder="Masukan username">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Masukan password">
              </div>
              <!-- Bisa dimasukin captcha google biar lebih aman -->
              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>