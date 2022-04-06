<?php

session_start();
require_once("../config/db.php");
require_once("../helper/function.php");

$id = $_GET['id'];
if (!empty($_POST)) {
    // dd('masuk sini');
    // User submit form
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE barang SET idkategori=:idkategori, nama=:nama, harga=:harga, stock=:stok, updated_at=:tgl WHERE id=:id";

    try {
        //code...
        $command = $conn->prepare($sql);
        $command->bindParam("nama", $nama);
        $command->bindParam("idkategori", $kategori);
        $command->bindParam("harga", $harga);
        $command->bindParam("stok", $stok);
        $command->bindParam("tgl", $now);
        $command->bindParam("id", $id);
        $command->execute();
        flash('barang', 'Berhasil mengubah data barang', 'success');
    } catch (Exception $e) {
        flash('barang', 'Terjadi kesalahan saat mengubah data barang ' . $e->getMessage(), 'error');
    }
    // dd($_SESSION);
    header("location: /barang/index.php");
    return;
}
// Ambil daftar kategori
$kategori = $conn
    ->query("SELECT id, kategori FROM kategori WHERE TRUE")
    ->fetchAll();
// Awal load, select data barang
$sql = "SELECT barang.id, idkategori, nama, kategori, harga, stock FROM barang 
INNER JOIN kategori on kategori.id=barang.idkategori
WHERE barang.id = $id";
$barang = $conn
    ->query($sql)
    ->fetch();
// dd($barang);
// dd($dataCount);

require_once("../layout/header.php");

?>

<h3 class="mb-3">Ubah Barang</h3>

<form method="POST" action="">
    <div class="form-group">
        <label for="idkategori">Kategori</label>
        <select class="form-control" name="kategori" id="kategori">
            <?php foreach ($kategori as $i => $kategori) : ?>
                <?php 
                    $selected='';
                    if ($kategori['id'] == $barang['idkategori']) $selected = 'selected';
                ?>
                <option value="<?= $kategori['id'] ?>" <?= $selected ?>><?= $kategori['kategori'] ?></option>
            <?php endforeach ?>
        </select>
        <small id="emailHelp" class="form-text text-muted">Kategori barang yang hendak ditambahkan.</small>

        <label for="exampleInputEmail1">Nama Barang</label>
        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nama" value="<?= $barang['nama'] ?>">
        <small id="emailHelp" class="form-text text-muted">Nama barang yang hendak ditambahkan.</small>

        <label for="harga">Harga</label>
        <input type="number" class="form-control" id="harga" aria-describedby="emailHelp" name="harga" required value="<?= $barang['harga'] ?>">
        <small id="emailHelp" class="form-text text-muted">Harga barang yang hendak ditambahkan.</small>

        <label for="stok">Stok</label>
        <input type="number" class="form-control" id="stok" aria-describedby="emailHelp" name="stok" required value="<?= $barang['stock'] ?>">
        <small id="emailHelp" class="form-text text-muted">Stok barang yang hendak ditambahkan.</small>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?php

require_once("../layout/footer.php");

?>