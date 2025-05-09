 <!--sidebar end-->

 <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
 <!--main content start-->
<?php 
	$id = $_GET['produk'];
	$hasil = $lihat->produk_edit($id);
?>
<a href="index.php?page=produk" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Balik </a>
<h4>Edit Produk</h4>
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
    <p>Edit Data Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
    <p>Hapus Data Berhasil !</p>
</div>
<?php }?>
<div class="card card-body">
	<div class="table-responsive">
		<table class="table table-striped">
			<form action="fungsi/edit/edit.php?produk=edit" method="POST">
				<tr>
					<td>ID Produk</td>
					<td><input type="text" readonly class="form-control" value="<?= $hasil['id_produk']; ?>" name="id"></td>
				</tr>
				<tr>
					<td>Kategori</td>
					<td>
						<select name="kategori" class="form-control">
							<option value="<?= $hasil['id_kategori']; ?>"><?= $hasil['nama_kategori']; ?></option>
							<option value="#">Pilih Kategori</option>
							<?php $kat = $lihat->kategori(); foreach($kat as $isi){ ?>
							<option value="<?= $isi['id_kategori']; ?>"><?= $isi['nama_kategori']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Nama Produk</td>
					<td><input type="text" class="form-control" value="<?= $hasil['nama_produk']; ?>" name="nama"></td>
				</tr>
				<tr>
					<td>Harga Jual</td>
					<td><input type="number" class="form-control" value="<?= $hasil['harga_jual']; ?>" name="jual"></td>
				</tr>
				<tr>
					<td>Satuan</td>
					<td>
						<select name="satuan" class="form-control">
							<option value="<?= $hasil['satuan']; ?>"><?= $hasil['satuan']; ?></option>
							<option value="#">Pilih Satuan</option>
							<option value="Cup">Cup</option>
							<option value="Porsi">Porsi</option>
							<option value="Botol">Botol</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Tanggal Update</td>
					<td><input type="text" readonly class="form-control" value="<?= date("j F Y, G:i"); ?>" name="tgl"></td>
				</tr>
				<tr>
					<td></td>
					<td><button class="btn btn-primary"><i class="fa fa-edit"></i> Update Data</button></td>
				</tr>
			</form>
		</table>
	</div>
</div>
