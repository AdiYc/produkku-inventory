<?php
    session_start();
    if($_SESSION["login"] != true){
        header("Location: ../login.php");
        exit();
    }

require '../config/koneksi.php';
$id = $_GET['id'];
mysqli_query($conn,"delete from produk where id='$id'");
header("location: ../index.php");
?>