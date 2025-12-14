<?php
include 'koneksi.php';

/* ===== TAMBAH ===== */
if(isset($_POST['tambah'])){
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp,"assets/image/".$gambar);

    mysqli_query($koneksi,"
        INSERT INTO produk_baju (nama_produk,harga,stok,gambar)
        VALUES ('$nama','$harga','$stok','$gambar')
    ");
    header("Location: kelola_produk.php");
}

/* ===== UPDATE ===== */
if(isset($_POST['update'])){
    $id    = $_POST['id'];
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    if($_FILES['gambar']['name']!=""){
        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp,"assets/image/".$gambar);

        mysqli_query($koneksi,"
            UPDATE produk_baju SET
            nama_produk='$nama',
            harga='$harga',
            stok='$stok',
            gambar='$gambar'
            WHERE id_produk='$id'
        ");
    } else {
        mysqli_query($koneksi,"
            UPDATE produk_baju SET
            nama_produk='$nama',
            harga='$harga',
            stok='$stok'
            WHERE id_produk='$id'
        ");
    }
    header("Location: kelola_produk.php");
}

/* ===== HAPUS ===== */
if(isset($_GET['hapus'])){
    mysqli_query($koneksi,"DELETE FROM produk_baju WHERE id_produk=$_GET[hapus]");
    header("Location: kelola_produk.php");
}

/* ===== EDIT ===== */
$edit = false;
if(isset($_GET['edit'])){
    $edit = mysqli_fetch_assoc(
        mysqli_query($koneksi,"SELECT * FROM produk_baju WHERE id_produk=$_GET[edit]")
    );
}

$data = mysqli_query($koneksi,"SELECT * FROM produk_baju ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box
    }

    body {
        font-family: Arial;
        background: #f1f3f6
    }

    /* LAYOUT */
    .wrapper {
        display: flex;
        height: 100vh
    }

    .sidebar {
        width: 220px;
        background: #a31c8d;
        color: white;
        padding: 20px
    }

    .sidebar h2 {
        margin-bottom: 30px
    }

    .sidebar a {
        display: block;
        color: white;
        text-decoration: none;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 10px
    }

    .sidebar a:hover {
        background: rgba(255, 255, 255, .2)
    }

    .main {
        flex: 1;
        padding: 25px;
        overflow: auto
    }

    /* CARD */
    .card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, .1)
    }

    /* FORM */
    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 6px;
        border: 1px solid #ccc
    }

    button {
        padding: 10px;
        background: #a31c8d;
        border: none;
        color: white;
        border-radius: 6px;
        cursor: pointer
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse
    }

    th,
    td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: center
    }

    th {
        background: #f5f5f5
    }

    .btn {
        padding: 6px 10px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        font-size: 13px
    }

    .edit {
        background: #2196f3
    }

    .hapus {
        background: #e53935
    }

    img {
        border-radius: 6px
    }
    </style>
</head>

<body>

    <div class="wrapper">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="kelola_produk.php">📦 Kelola Produk</a>
            <a href="index.php">🏠 Lihat Toko</a>
        </div>

        <!-- MAIN -->
        <div class="main">

            <div class="card">
                <h3><?= $edit ? "Edit Produk" : "Tambah Produk"; ?></h3>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $edit['id_produk'] ?? '' ?>">

                    <input type="text" name="nama" placeholder="Nama Produk" value="<?= $edit['nama_produk'] ?? '' ?>"
                        required>

                    <input type="number" name="harga" placeholder="Harga" value="<?= $edit['harga'] ?? '' ?>" required>

                    <input type="number" name="stok" placeholder="Stok" value="<?= $edit['stok'] ?? '' ?>" required>

                    <input type="file" name="gambar" <?= $edit ? "" : "required"; ?>>

                    <button name="<?= $edit ? "update" : "tambah"; ?>">
                        <?= $edit ? "Update Produk" : "Tambah Produk"; ?>
                    </button>
                </form>
            </div>

            <div class="card">
                <h3>Data Produk</h3>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                    <?php $no=1; while($r=mysqli_fetch_assoc($data)){ ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $r['nama_produk']; ?></td>
                        <td>Rp <?= number_format($r['harga']); ?></td>
                        <td><?= $r['stok']; ?></td>
                        <td><img src="assets/image/<?= $r['gambar']; ?>" width="60"></td>
                        <td>
                            <a class="btn edit" href="?edit=<?= $r['id_produk']; ?>">Edit</a>
                            <a class="btn hapus" href="?hapus=<?= $r['id_produk']; ?>"
                                onclick="return confirm('Hapus produk?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

        </div>
    </div>

</body>

</html>