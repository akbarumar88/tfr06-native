<?php

require_once("../layout/header.php");
require_once("../config/db.php");
require_once("../helper/function.php");

$entri = !empty($_GET['entri']) ? $_GET['entri'] : 10;
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $entri;
$cari = !empty($_GET['q']) ? $_GET['q'] : '';

$data = $conn
    ->query("SELECT id, kategori FROM kategori WHERE kategori LIKE '%$cari%' LIMIT $entri OFFSET $offset")
    ->fetchAll();

$dataCount = $conn
    ->query("SELECT COUNT(id) as count FROM kategori WHERE kategori LIKE '%$cari%'")
    ->fetch()['count'];

$pageSize = 10;
// dd($dataCount);

?>

<h3 class="mb-3">Data Kategori</h3>

<a href="/admin/kategori/create" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a>
<form action="<?= '' ?>/admin/kategori/exportpdf" class="d-inline" method="POST">
    <input type="hidden" name="q" value="<?= $_GET['q'] ?>">
    <button type="submit" class="btn btn-warning"><i class="fa fa-print"></i> Export PDF</button>
</form>
<form action="<?= '' ?>/admin/kategori/exportexcel" class="d-inline" method="POST">
    <input type="hidden" name="q" value="<?= $_GET['q'] ?>">
    <button type="submit" class="btn btn-warning"><i class="fa fa-file-excel"></i> Export Excel</button>
</form>

<div class="datatable-wrapper shadow-lg rounded mt-4">
    <div class="datatable-heading p-4 border-bottom">
        <b>Data Kategori</b>
    </div>

    <div class="datatable-content p-4">
        <div class="datatable-search-wrap d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <p class="mb-0">Menampilkan</p>

                <form action="" class="mx-2">
                    <input type="hidden" name="page" value="<?= $_GET['page'] ?>">
                    <input type="hidden" name="entri" value="<?= $_GET['q'] ?>">
                    <select onchange="this.form.submit()" name="entri" id="" class="form-control py-0 px-2">
                        <option value="10" <?= $entri == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $entri == 25 ? 'selected' : '' ?>>25</option>
                    </select>
                </form>

                <p class="mb-0">entri</p>
            </div>
            <div class="d-flex align-items-center">
                <p class="mb-0 mr-2">Pencarian: </p>
                <form action="" method="get">
                    <input type="hidden" name="page" value="<?= $_GET['page'] ?>">
                    <input type="hidden" name="entri" value="<?= $_GET['entri'] ?>">
                    <input type="text" name="q" id="" class="form-control" value="<?= $_GET['q'] ?>" />
                </form>
            </div>
        </div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $i => $kategori) : ?>
                    <tr>
                        <th scope="row"><?= $i + 1 ?></th>
                        <td><?= $kategori['kategori'] ?></td>
                        <td>
                            <div class="d-flex">
                                <a href="/kategori/edit.php?id=<?= $kategori->id ?>" class="btn btn-warning mr-2"><i class="fa fa-edit"></i> Edit</a>

                                <form action="/kategori/hapus.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $kategori->id ?>">
                                    <button onclick="return confirm('Apakah anda yakin ingin menghapus data?')" type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>
                                        Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= renderPaginationLinks($dataCount, $entri)  ?>

    </div>


</div>

<?php

require_once("../layout/footer.php");

?>