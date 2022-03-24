<?php

$host = "localhost";
$db = "tfr05";
$username = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Koneksi Berhasil";
} catch (PDOException $e) {
    echo "Koneksi Gagal: " . $e->getMessage();
    // throw $e;
}
