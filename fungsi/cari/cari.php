<?php
session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';

    if (!empty($_GET['cari_produk'])) {
        // Ambil dan sanitasi input dari form
        $cari = trim(strip_tags($_POST['keyword']));

        if ($cari != '') {
            // Query untuk mencari produk
            $sql = "SELECT * FROM produk WHERE id_produk LIKE ? OR nama_produk LIKE ? ORDER BY nama_produk ASC";
            $row = $config->prepare($sql);
            $row->execute(array('%'.$cari.'%', '%'.$cari.'%'));
            $hasil = $row->fetchAll(PDO::FETCH_ASSOC);

            // Cek apakah ada hasil
            if (count($hasil) > 0) {
                ?>
                <table class="table table-bordered table-hover" width="100%" id="example2">
                    <thead class="thead-light">
                        <tr>
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga Jual</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($hasil as $item) { ?>
                        <tr>
                            <td><?php echo htmlentities($item['id_produk']); ?></td>
                            <td><?php echo htmlentities($item['nama_produk']); ?></td>
                            <td>Rp. <?php echo number_format($item['harga_jual']); ?></td>
                            <td><?php echo htmlentities($item['satuan']); ?></td>
                            <td>
                                <form method="GET" action="fungsi/tambah/tambah.php" style="display: inline;">
                                    <input type="hidden" name="jual" value="jual">
                                    <input type="hidden" name="id" value="<?php echo htmlentities($item['id_produk']); ?>">
                                    <input type="hidden" name="id_kasir" value="<?php echo $_SESSION['admin']['id_member']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Tambahkan <?php echo htmlentities($item['nama_produk']); ?> ke keranjang?')">
                                        <i class="fa fa-shopping-cart"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Produk tidak ditemukan!</div>';
            }
        } else {
            echo '<div class="alert alert-danger"><i class="fa fa-times"></i> Keyword tidak boleh kosong!</div>';
        }
    }
} else {
    echo '<script>window.location="../../index.php?page=login"</script>';
}
?>
