<?php

session_start();
require_once("../config/db.php");
require_once("../helper/function.php");

if (!empty($_POST)) {
    // dd('masuk sini');
    // User submit form
    $kategori = $_POST['kategori'];
    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO kategori(kategori, created_at, updated_at) VALUES(:kategori, :tgl, :tgl)";

    try {
        //code...
        $command = $conn->prepare($sql);
        $command->bindParam("kategori", $kategori);
        $command->bindParam("tgl", $now);
        $command->execute();
        flash('kategori', 'Berhasil menambahkan data kategori', 'success');
    } catch (Exception $e) {
        flash('kategori', 'Terjadi kesalahan saat menambahkan data kategori ' . $e->getMessage(), 'error');
    }
    // dd($_SESSION);
    header("location: /kategori/index.php");
    return;
}
// dd($dataCount);

require_once("../layout/header.php");

?>

<h3 class="mb-3">Tambah Kategori</h3>

<form method="POST" action="">
    <div class="form-group">
        <label for="exampleInputEmail1">Nama Kategori</label>
        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="kategori">
        <small id="emailHelp" class="form-text text-muted">Nama kategori yang hendak ditambahkan.</small>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?php

require_once("../layout/footer.php");

?>