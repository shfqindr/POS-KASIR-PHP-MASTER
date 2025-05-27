<?php
session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';

    if (!empty($_GET['kategori'])) {
        $nama = htmlentities($_POST['kategori']);
        $tgl = date("j F Y, G:i");
        $data[] = $nama;
        $data[] = $tgl;
        $sql = 'INSERT INTO kategori (nama_kategori, tgl_input) VALUES (?, ?)';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=kategori&success=tambah-data"</script>';
    }

    // Menambahkan produk baru
    if (!empty($_GET['produk'])) {
        $id = htmlentities($_POST['id']);
        $kategori = htmlentities($_POST['kategori']);
        $nama = htmlentities($_POST['nama']);
        $harga_jual = htmlentities($_POST['jual']);
        $satuan = htmlentities($_POST['satuan']);
        $tgl = date("j F Y, G:i");

        $data[] = $id;
        $data[] = $kategori;
        $data[] = $nama;
        $data[] = $harga_jual;
        $data[] = $satuan;
        $data[] = $tgl;
        $sql = 'INSERT INTO produk (id_produk, id_kategori, nama_produk, harga_jual, satuan, tgl_input) 
                VALUES (?, ?, ?, ?, ?, ?)';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=produk&success=tambah-data"</script>';
    }

    // Menambahkan ke keranjang
    if (!empty($_GET['jual'])) {
        try {
            $id = htmlentities($_GET['id']);
            
            // Ambil data produk berdasarkan id_produk
            $sql = 'SELECT * FROM produk WHERE id_produk = ?';
            $row = $config->prepare($sql);
            $row->execute(array($id));
            $hsl = $row->fetch();

            // Cek apakah produk ditemukan
            if (!$hsl) {
                echo '<script>alert("Produk tidak ditemukan!"); window.location="../../index.php?page=jual"</script>';
                exit;
            }

            $id_member = $_SESSION['admin']['id_member']; // Menggunakan id_member dari session
            $jumlah = 1; // Default jumlah

            // Cek apakah produk sudah ada di keranjang
            $sql_cek = 'SELECT * FROM penjualan WHERE id_produk = ? AND id_member = ?';
            $row_cek = $config->prepare($sql_cek);
            $row_cek->execute(array($id, $id_member));
            $cek_keranjang = $row_cek->fetch();

            if ($cek_keranjang) {
                // Jika sudah ada, update jumlah
                $jumlah_baru = $cek_keranjang['jumlah'] + 1;
                $sql_update = 'UPDATE penjualan SET jumlah = ? WHERE id_penjualan = ?';
                $row_update = $config->prepare($sql_update);
                $row_update->execute(array($jumlah_baru, $cek_keranjang['id_penjualan']));
            } else {
                // Jika belum ada, insert baru
                // Generate id_nota dengan format YYMMDD + 2 digit counter
                $tanggal_nota = date("ymd"); // Format YYMMDD
                
                // Cari counter terakhir untuk tanggal ini
                $sql_counter = "SELECT MAX(CAST(SUBSTRING(id_nota, 7, 2) AS UNSIGNED)) as max_counter 
                               FROM penjualan 
                               WHERE SUBSTRING(id_nota, 1, 6) = ?";
                $row_counter = $config->prepare($sql_counter);
                $row_counter->execute(array($tanggal_nota));
                $result_counter = $row_counter->fetch();
                
                // Set counter
                $counter = 1;
                if ($result_counter['max_counter'] !== null) {
                    $counter = $result_counter['max_counter'] + 1;
                }
                
                // Format id_nota dengan padding 2 digit
                $id_nota = $tanggal_nota . str_pad($counter, 2, '0', STR_PAD_LEFT);
                
                // Data untuk insert
                $data1[] = $id_nota;        // id_nota
                $data1[] = $id;             // id_produk
                $data1[] = $id_member;      // id_member
                $data1[] = $jumlah;         // jumlah

                $sql1 = 'INSERT INTO penjualan (id_nota, id_produk, id_member, jumlah) 
                        VALUES (?, ?, ?, ?)';
                $row1 = $config->prepare($sql1);
                $row1->execute($data1);
            }

            echo '<script>window.location="../../index.php?page=jual&success=tambah-data#keranjang"</script>';

        } catch (Exception $e) {
            echo '<script>alert("Terjadi kesalahan: ' . $e->getMessage() . '"); window.location="../../index.php?page=jual"</script>';
        }
    }
} else {
    echo '<script>window.location="../../index.php?page=login"</script>';
}
?>
