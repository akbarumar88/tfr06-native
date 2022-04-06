<?php

session_start();
require_once("../config/db.php");
require_once("../helper/function.php");

$id = $_GET['id'];
if (!empty($_POST)) {
    // dd('masuk sini');
    // User submit form
    $kategori = $_POST['kategori'];
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE kategori SET kategori=:kategori, updated_at=:tgl WHERE id=:id";

    try {
        //code...
        $command = $conn->prepare($sql);
        $command->bindParam("kategori", $kategori);
        $command->bindParam("tgl", $now);
        $command->bindParam("id", $id);
        $command->execute();
        flash('kategori', 'Berhasil mengubah data kategori', 'success');
    } catch (Exception $e) {
        flash('kategori', 'Terjadi kesalahan saat mengubah data kategori ' . $e->getMessage(), 'error');
    }
    // dd($_SESSION);
    header("location: /kategori/index.php");
    return;
}

// Awal load, select data kategori
$sql = "SELECT id, kategori FROM kategori WHERE id = $id";
$kategori = $conn
    ->query($sql)
    ->fetch();
// dd($kategori);
// dd($dataCount);

require_once("../layout/header.php");

?>

<h3 class="mb-3">Ubah Kategori</h3>

<form method="POST" action="">
    <div class="form-group">
        <label for="exampleInputEmail1">Nama Kategori</label>
        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="kategori" value="<?= $kategori['kategori'] ?>">
        <small id="emailHelp" class="form-text text-muted">Nama kategori yang hendak ditambahkan.</small>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?php

require_once("../layout/footer.php");

?>