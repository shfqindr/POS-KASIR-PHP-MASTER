<?php 
	$id = $_GET['produk'];
	$hasil = $lihat->produk_edit($id);
?>
<a href="index.php?page=produk" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Balik </a>
<h4>Detail Produk</h4>
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
	<p>Tambah Data Berhasil!</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
	<p>Hapus Data Berhasil!</p>
</div>
<?php }?>
<div class="card card-body">
	<div class="table-responsive">
		<table class="table table-striped">
			<tr>
				<td>ID Produk</td>
				<td><?= $hasil['id_produk']; ?></td>
			</tr>
			<tr>
				<td>Kategori</td>
				<td><?= $hasil['nama_kategori']; ?></td>
			</tr>
			<tr>
				<td>Nama Produk</td>
				<td><?= $hasil['nama_produk']; ?></td>
			</tr>
			<tr>
				<td>Harga</td>
				<td><?= number_format($hasil['harga_jual'], 0, ',', '.'); ?></td>
			</tr>
			<tr>
				<td>Satuan</td>
				<td><?= $hasil['satuan']; ?></td>
			</tr>
			<tr>
				<td>Tanggal Input</td>
				<td><?= $hasil['tgl_input']; ?></td>
			</tr>
			<tr>
				<td>Tanggal Update</td>
				<td><?= $hasil['tgl_update']; ?></td>
			</tr>
		</table>
	</div>
</div>
