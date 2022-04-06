<?php 

session_start();
require_once("../config/db.php");
require_once("../helper/function.php");

$id = $_POST['id'];

try {
    //code...
    // Select data terlebih dahulu (Pengecekan constraint).
    $sql = "SELECT id FROM barang where idkategori=$id";
    $barang = $conn
        ->query($sql)
        ->fetch();
    // dd($barang);
    if (!empty($barang)) {
        flash('kategori', 'Tidak dapat menghapus data kategori, data kategori sudah terpakai', 'error');    
    } else {
        // Aman, hapus data kategori
        $sql = "DELETE FROM kategori where id=:id";
        $cmd = $conn->prepare($sql);
        $cmd->bindParam("id", $id);
        $cmd->execute();
        flash('kategori', 'Berhasil menghapus data kategori', 'success');
    }

} catch (Exception $e) {
    flash('kategori', 'Terjadi kesalahan saat menghapus data kategori ' . $e->getMessage(), 'error');
}
header("location: /kategori/index.php");

?>