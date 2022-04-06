<?php 

session_start();
require_once("../config/db.php");
require_once("../helper/function.php");

$id = $_POST['id'];

try {
    // dd($barang);
    // if (!empty($barang)) {
    //     flash('barang', 'Tidak dapat menghapus data barang, data barang sudah terpakai', 'error');    
    // } else {
    //     // Aman, hapus data barang
    // }
    $sql = "DELETE FROM barang where id=:id";
    $cmd = $conn->prepare($sql);
    $cmd->bindParam("id", $id);
    $cmd->execute();
    flash('barang', 'Berhasil menghapus data barang', 'success');

} catch (Exception $e) {
    flash('barang', 'Terjadi kesalahan saat menghapus data barang ' . $e->getMessage(), 'error');
}
header("location: /barang/index.php");

?>