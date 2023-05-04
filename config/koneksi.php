<?php

    // KONEKSI PHP COI WLEEE
    // GUE ANAK KONEKSI PHP COI COI COI UNVIERSTY

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "phpdb";

    $conn = new mysqli($host, $user, $password, $database);

    if($conn->connect_error)
    {
        die("Gagal terhubung ke database".$conn->connect_error);
    }
?>