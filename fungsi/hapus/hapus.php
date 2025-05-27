<?php

session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';

    // Hapus kategori
    if (!empty(htmlentities($_GET['kategori']))) {
        $id = htmlentities($_GET['id']);
        $data[] = $id;
        $sql = 'DELETE FROM kategori WHERE id_kategori = ?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=kategori&remove=hapus-data"</script>';
    }

    // Hapus produk
    if (!empty(htmlentities($_GET['barang']))) {
        $id = htmlentities($_GET['id']);
        $data[] = $id;
        $sql = 'DELETE FROM produk WHERE id_produk = ?'; // Menggunakan tabel produk
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=barang&remove=hapus-data"</script>';
    }

    // Hapus dari keranjang penjualan
    if (!empty(htmlentities($_GET['jual']))) {
        $id = htmlentities($_GET['id']); // id_penjualan
        $data[] = $id;
        $sql = 'DELETE FROM penjualan WHERE id_penjualan = ?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=jual&remove=hapus-data"</script>';
    }

    // Hapus semua penjualan
    if (!empty(htmlentities($_GET['penjualan']))) {
        $sql = 'DELETE FROM penjualan';
        $row = $config->prepare($sql);
        $row->execute();
        echo '<script>window.location="../../index.php?page=jual&remove=hapus-data"</script>';
    }
    
    // Hapus semua nota
    if (!empty(htmlentities($_GET['laporan']))) {
        $sql = 'DELETE FROM nota';
        $row = $config->prepare($sql);
        $row->execute();
        echo '<script>window.location="../../index.php?page=laporan&remove=hapus"</script>';
    }
} else {
    echo '<script>window.location="../../index.php?page=login"</script>';
}
?>
