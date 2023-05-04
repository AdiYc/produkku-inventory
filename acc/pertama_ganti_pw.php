<?php
session_start();
if ($_SESSION["login"] != true) {
  header("Location: ../login.php");
  exit();
}

if ($_SESSION["pertama_ganti_pw"] != 1) {
  header("Location: ../index.php");
  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produkku - Ganti Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <h1 class="text-center my-5">Ganti Password</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      require "../config/koneksi.php";

      $passwordlama = $_POST['passwordlama'];
      $passwordbaru = $_POST['passwordbaru'];
      $konfirmasipassword = $_POST['konfirmasipassword'];

      $id = $_SESSION['id'];
      $error = false;


      $slc = mysqli_prepare($conn, "SELECT password FROM akun WHERE id = ? ");
      mysqli_stmt_bind_param($slc, "i", $id);
      mysqli_stmt_execute($slc);
      $hasil = mysqli_stmt_get_result($slc);
      $row = mysqli_fetch_assoc($hasil);

      if ($row['password'] != $passwordlama) {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> Password saat ini anda salah!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        $error = true;
      }

      if (!$error) {
        if (strlen($passwordbaru) <= 8) {
          echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> Password minimal delapan karakter!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
          $error = true;
        }
      }

      if (!$error) {
        if ($passwordbaru != $konfirmasipassword) {
          echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> Password baru sama konfirmasi password tidak sesuai!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
          $error = true;
        }
      }

      if (!$error) {
        $zero = 0;
        $stmt = mysqli_prepare($conn, "UPDATE akun SET password = ?, pertama_ganti_pw = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sii", $passwordbaru, $zero, $id);
        mysqli_stmt_execute($stmt);
        mysqli_close($conn);
        $_SESSION['pertama_ganti_pw'] = 0;
        header("Location: ../index.php");
      }
    }
    ?>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" autocomplete="off">
          <div class="mb-3">
            <label for="passwordlama" class="form-label">Password Saat Ini</label>
            <div class="input-group">
              <input type="password" class="form-control" id="passwordlama" name="passwordlama" required>
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            </div>
          </div>
          <div class="mb-3">
            <label for="passwordbaru" class="form-label">Password Baru</label>
            <div class="input-group">
              <input type="password" class="form-control" id="passwordbaru" name="passwordbaru" required>
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            </div>
          </div>
          <div class="mb-3">
            <label for="konfirmasipassword" class="form-label">Konfirmasi Password Baru</label>
            <div class="input-group">
              <input type="password" class="form-control" id="konfirmasipassword" name="konfirmasipassword" required>
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>