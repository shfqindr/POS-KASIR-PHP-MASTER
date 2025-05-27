<?php 
$id = isset($_GET['produk']) ? htmlentities($_GET['produk']) : null;

if ($id) {
    $hasil = $lihat->produk_edit($id);
} else {
    echo '<script>alert("ID produk tidak ditemukan!"); window.location="index.php?page=produk";</script>';
    exit;
}
?>
<a href="index.php?page=produk" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Balik </a>
<h4>Detail Produk</h4>
&nbsp;
&nbsp;

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">
    <p>Tambah Data Berhasil!</p>
</div>
<?php endif; ?>
&nbsp;
&nbsp;

<?php if (isset($_GET['remove'])): ?>
<div class="alert alert-danger">
    <p>Hapus Data Berhasil!</p>
</div>
<?php endif; ?>
&nbsp;
&nbsp;

<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <td>ID Produk</td>
                <td><?= htmlspecialchars($hasil['id_produk']); ?></td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td><?= htmlspecialchars($hasil['nama_kategori']); ?></td>
            </tr>
            <tr>
                <td>Nama Produk</td>
                <td><?= htmlspecialchars($hasil['nama_produk']); ?></td>
            </tr>
            <tr>
                <td>Harga</td>
                <td>Rp. <?= number_format($hasil['harga_jual'], 0, ',', '.'); ?>,-</td>
            </tr>
            <tr>
                <td>Satuan</td>
                <td><?= htmlspecialchars($hasil['satuan']); ?></td>
            </tr>
            <tr>
                <td>Tanggal Input</td>
                <td><?= htmlspecialchars($hasil['tgl_input']); ?></td>
            </tr>
            <tr>
                <td>Tanggal Update</td>
                <td><?= htmlspecialchars($hasil['tgl_update']); ?></td>
            </tr>
        </table>
    </div>
</div>