<?php 
	@ob_start();
	session_start();
	if(!empty($_SESSION['admin'])){ }else{
		echo '<script>window.location="login.php";</script>';
        exit;
	}
	require 'config.php';
	include $view;
	$lihat = new view($config);
	$toko = $lihat -> toko();
	
	// Ambil data penjualan dengan JOIN ke tabel produk untuk mendapatkan nama_produk dan harga_jual
	$id_member = $_SESSION['admin']['id_member'];
	$sql = "SELECT p.*, pr.nama_produk, pr.harga_jual 
			FROM penjualan p 
			JOIN produk pr ON p.id_produk = pr.id_produk 
			WHERE p.id_member = ?";
	$row = $config->prepare($sql);
	$row->execute(array($id_member));
	$hsl = $row->fetchAll();
?>
<html>
	<head>
		<title>Print Struk</title>
		<link rel="stylesheet" href="assets/css/bootstrap.css">
		<style>
			body { font-family: Arial, sans-serif; }
			.struk { max-width: 300px; margin: 0 auto; }
			table { font-size: 12px; }
			.text-center { text-align: center; }
			.text-right { text-align: right; }
			@media print {
				.no-print { display: none; }
			}
		</style>
	</head>
	<body>
		<script>window.print();</script>
		<div class="container">
			<div class="row">
				<div class="col-sm-4"></div>
				<div class="col-sm-4 struk">
					<div class="text-center">
						<h4><?php echo $toko['nama_toko'];?></h4>
						<p><?php echo $toko['alamat_toko'];?></p>
						<p>Telp: <?php echo $toko['tlp'];?></p>
						<hr>
						<p>Tanggal: <?php echo date("j F Y, G:i");?></p>
						<p>Kasir: <?php echo htmlentities($_GET['nama_member']);?></p>
						<hr>
					</div>
					
					<table class="table table-bordered" style="width:100%;">
						<thead>
							<tr>
								<th>No.</th>
								<th>Produk</th>
								<th>Qty</th>
								<th>Harga</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$no = 1; 
							$total_semua = 0;
							foreach($hsl as $isi): 
								$total_item = $isi['harga_jual'] * $isi['jumlah'];
								$total_semua += $total_item;
							?>
							<tr>
								<td><?php echo $no;?></td>
								<td><?php echo htmlentities($isi['nama_produk']);?></td>
								<td><?php echo $isi['jumlah'];?></td>
								<td>Rp. <?php echo number_format($isi['harga_jual']);?></td>
								<td>Rp. <?php echo number_format($total_item);?></td>
							</tr>
							<?php 
							$no++; 
							endforeach;
							?>
						</tbody>
					</table>
					
					<hr>
					<div class="text-right">
						<strong>
							Total Belanja: Rp. <?php echo number_format($total_semua);?>,-<br>
							Bayar: Rp. <?php echo number_format(htmlentities($_GET['bayar']));?>,-<br>
							Kembali: Rp. <?php echo number_format(htmlentities($_GET['kembali']));?>,-
						</strong>
					</div>
					
					<hr>
					<div class="text-center">
						<p><strong>Terima Kasih Telah Berbelanja!</strong></p>
						<p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
						<small>Powered by POS System</small>
					</div>
				</div>
				<div class="col-sm-4"></div>
			</div>
		</div>
	</body>
</html>