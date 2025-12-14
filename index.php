<?php
include 'koneksi.php';

/* ================= SEARCH ================= */
$keyword = isset($_GET['search']) ? $_GET['search'] : '';

if($keyword != ''){
    $produk = mysqli_query($koneksi,"
        SELECT * FROM produk_baju 
        WHERE nama_produk LIKE '%$keyword%'
        ORDER BY id_produk DESC
    ");
} else {
    $produk = mysqli_query($koneksi,"
        SELECT * FROM produk_baju 
        ORDER BY id_produk DESC
    ");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Batik Nusantara</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box
    }

    body {
        font-family: Arial;
        background: #f4f4f4
    }

    /* NAVBAR */
    .navbar {
        background: #a31c8d;
        color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center
    }

    .navbar h2 {
        font-size: 22px
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin-left: 20px;
        font-weight: bold
    }

    /* SEARCH */
    .search-box {
        display: flex;
        gap: 8px
    }

    .search-box input {
        padding: 7px 12px;
        border-radius: 6px;
        border: none;
        width: 180px
    }

    .search-box button {
        background: white;
        color: #a31c8d;
        border: none;
        padding: 7px 12px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer
    }

    /* CONTENT */
    .container {
        padding: 30px
    }

    .title {
        margin-bottom: 20px;
        font-size: 20px;
        color: #333
    }

    /* GRID */
    .grid-produk {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px
    }

    /* CARD */
    .card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
        transition: .2s
    }

    .card:hover {
        transform: translateY(-5px)
    }

    .card img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        margin-bottom: 10px
    }

    .card h4 {
        font-size: 15px;
        color: #333;
        margin-bottom: 5px
    }

    .card p {
        color: #a31c8d;
        font-weight: bold;
        font-size: 14px
    }

    /* EMPTY */
    .empty {
        text-align: center;
        color: #777;
        margin-top: 50px
    }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <h2>Batik Nusantara</h2>

        <form class="search-box" method="get">
            <input type="text" name="search" placeholder="Cari batik..." value="<?= htmlspecialchars($keyword); ?>">
            <button>Cari</button>
        </form>

        <div>
            <a href="index.php">Beranda</a>
            <a href="kelola_produk.php">Kelola Produk</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">
        <div class="title">
            <?= $keyword ? "Hasil pencarian: \"$keyword\"" : "Produk Terbaru"; ?>
        </div>

        <div class="grid-produk">
            <?php if(mysqli_num_rows($produk) > 0){ ?>
            <?php while($p=mysqli_fetch_assoc($produk)){ ?>
            <div class="card">
                <img src="assets/image/<?= $p['gambar']; ?>">
                <h4><?= $p['nama_produk']; ?></h4>
                <p>Rp <?= number_format($p['harga']); ?></p>
            </div>
            <?php } ?>
            <?php } else { ?>
            <div class="empty">Produk tidak ditemukan 😢</div>
            <?php } ?>
        </div>
    </div>

</body>

</html>