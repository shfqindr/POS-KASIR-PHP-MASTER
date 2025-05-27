<?php 
$id = $_SESSION['admin']['id_member'];
$hasil = $lihat->member_edit($id);
?>
&nbsp;
&nbsp;

<h4>Keranjang Penjualan</h4>
<br>
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
	<p>Produk berhasil ditambahkan ke keranjang!</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
	<p>Hapus Data Berhasil !</p>
</div>
<?php }?>
&nbsp;
&nbsp;

<div class="row">
	<div class="col-sm-4">
		<div class="card card-primary mb-3">
			<div class="card-header bg-primary text-white">
				<h5><i class="fa fa-search"></i> Cari Produk</h5>
			</div>
			<div class="card-body">
				<div class="input-group">
					<input type="text" id="cari" class="form-control" name="cari" placeholder="Masukan ID / Nama Produk">
					<div class="input-group-append">
						<button class="btn btn-info" type="button" id="btn-cari">
							<i class="fa fa-search"></i> Cari
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="card card-primary mb-3">
			<div class="card-header bg-primary text-white">
				<h5><i class="fa fa-list"></i> Hasil Pencarian</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<div id="hasil_cari">
						<p class="text-muted">Masukkan ID/Nama produk untuk mencari...</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-sm-12">
		<div class="card card-primary">
			<div class="card-header bg-primary text-white">
				<h5><i class="fa fa-shopping-cart"></i> KASIR
				<a class="btn btn-danger float-right" 
					onclick="javascript:return confirm('Apakah anda ingin reset keranjang ?');" href="fungsi/hapus/hapus.php?penjualan=jual">
					<b>RESET KERANJANG</b></a>
				</h5>
			</div>
			<div class="card-body">
				<div id="keranjang" class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<td><b>Tanggal</b></td>
							<td><input type="text" readonly="readonly" class="form-control" value="<?php echo date("j F Y, G:i");?>" name="tgl"></td>
						</tr>
					</table>
					<table class="table table-bordered w-100" id="example1">
						<thead>
							<tr>
								<td>No</td>
								<td>Nama Produk</td>
								<td>Harga Satuan</td>
								<td style="width:10%;">Jumlah</td>
								<td style="width:20%;">Total</td>
								<td>Kasir</td>
								<td>Aksi</td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$total_bayar = 0; 
							$no = 1; 
							
							// Query untuk mengambil data penjualan dengan JOIN ke tabel produk
							$sql_penjualan = "SELECT p.*, pr.nama_produk, pr.harga_jual, m.nama_member 
											  FROM penjualan p 
											  JOIN produk pr ON p.id_produk = pr.id_produk 
											  JOIN member m ON p.id_member = m.id_member 
											  WHERE p.id_member = ? 
											  ORDER BY p.id_penjualan DESC";
							$row_penjualan = $config->prepare($sql_penjualan);
							$row_penjualan->execute(array($id));
							$hasil_penjualan = $row_penjualan->fetchAll();
							?>
							<?php if(count($hasil_penjualan) > 0): ?>
							<?php foreach($hasil_penjualan as $isi): 
								// Hitung total per item
								$total_item = $isi['harga_jual'] * $isi['jumlah'];
								$total_bayar += $total_item;
							?>
							<tr>
								<td><?php echo $no;?></td>
								<td><?php echo htmlentities($isi['nama_produk']);?></td>
								<td>Rp. <?php echo number_format($isi['harga_jual']);?>,-</td>
								<td>
									<form method="POST" action="fungsi/edit/edit.php?jual=jual">
										<input type="number" name="jumlah" value="<?php echo $isi['jumlah'];?>" class="form-control" min="1">
										<input type="hidden" name="id" value="<?php echo $isi['id_penjualan'];?>">
										<input type="hidden" name="id_produk" value="<?php echo $isi['id_produk'];?>">
								</td>
								<td>Rp. <?php echo number_format($total_item);?>,-</td>
								<td><?php echo htmlentities($isi['nama_member']);?></td>
								<td>
									<button type="submit" class="btn btn-warning btn-sm">
										<i class="fa fa-edit"></i> Update
									</button>
									</form>
									<a href="fungsi/hapus/hapus.php?jual=jual&id=<?php echo $isi['id_penjualan'];?>&brg=<?php echo $isi['id_produk'];?>&jml=<?php echo $isi['jumlah']; ?>" 
										class="btn btn-danger btn-sm" onclick="return confirm('Hapus item ini?')">
										<i class="fa fa-times"></i> Hapus
									</a>
								</td>
							</tr>
							<?php 
							$no++; 
							endforeach;
							?>
							<?php else: ?>
							<tr>
								<td colspan="7" class="text-center">Keranjang masih kosong</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
					<br/>
					
					<div id="kasirnya">
						<table class="table table-striped">
							<?php
							// Proses bayar dan ke nota
							$hitung = 0;
							$bayar = 0;
							if(!empty($_GET['nota'] == 'yes')) {
								$total = $_POST['total'];
								$bayar = $_POST['bayar'];
								if(!empty($bayar)) {
									$hitung = $bayar - $total;
									if($bayar >= $total) {
										// Ambil data dari keranjang untuk disimpan ke nota
										foreach($hasil_penjualan as $item) {
											$total_per_item = $item['harga_jual'] * $item['jumlah'];
											$d = array(
												$item['id_produk'], 
												$item['id_member'], 
												$item['jumlah'], 
												$total_per_item, 
												date('Y-m-d H:i:s'), 
												date('m-Y')
											);
											$sql = "INSERT INTO nota (id_produk, id_member, jumlah, total, tanggal_input, periode) VALUES(?,?,?,?,?,?)";
											$row = $config->prepare($sql);
											$row->execute($d);
										}
										echo '<script>alert("Belanjaan Berhasil Di Bayar !");</script>';
										echo '<script>setTimeout(function(){window.location.href="fungsi/hapus/hapus.php?penjualan=jual";}, 1000);</script>';
									} else {
										echo '<script>alert("Uang Kurang ! Rp.'.number_format(abs($hitung)).'");</script>';
									}
								}
							}
							?>
							<!-- Form pembayaran -->
							<?php if(count($hasil_penjualan) > 0): ?>
							<form method="POST" action="index.php?page=jual&nota=yes#kasirnya">
								<tr>
									<td width="20%"><strong>Total Semua</strong></td>
									<td width="30%">
										<input type="text" class="form-control" name="total" value="<?php echo $total_bayar;?>" readonly>
									</td>
									<td width="15%"><strong>Bayar</strong></td>
									<td width="30%">
										<input type="number" class="form-control" name="bayar" placeholder="Masukkan jumlah bayar" required>
									</td>
									<td width="5%">
										<button class="btn btn-success" type="submit">
											<i class="fa fa-shopping-cart"></i> Bayar
										</button>
									</td>
								</tr>
							</form>
							<?php endif; ?>
							
							<tr>
								<td><strong>Kembali</strong></td>
								<td>
									<input type="text" class="form-control" value="<?php echo isset($hitung) ? 'Rp. '.number_format($hitung) : 'Rp. 0';?>" readonly>
								</td>
								<td colspan="3">
									<?php if(!empty($_GET['nota'] == 'yes') && isset($hitung) && $hitung >= 0): ?>
									<a href="print.php?nama_member=<?php echo $_SESSION['admin']['nama_member'];?>&bayar=<?php echo $bayar;?>&kembali=<?php echo $hitung;?>" target="_blank" class="btn btn-secondary">
										<i class="fa fa-print"></i> Print Bukti Pembayaran
									</a>
									<a class="btn btn-danger ml-2" href="fungsi/hapus/hapus.php?penjualan=jual">
										<i class="fa fa-refresh"></i> Transaksi Baru
									</a>
									<?php endif; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
&nbsp;
&nbsp;

<script>
$(document).ready(function(){
	
	// Fungsi pencarian menggunakan file cari.php
	function cariProduk() {
		var keyword = $("#cari").val().trim();
		
		if(keyword.length > 0) {
			$.ajax({
				type: "POST",
				url: "fungsi/cari/cari.php?cari_produk=true",
				data: {
					keyword: keyword
				},
				beforeSend: function(){
					$("#hasil_cari").html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Mencari produk...</div>');
				},
				success: function(response){
					$("#hasil_cari").html(response);
				},
				error: function(xhr, status, error){
					console.log('Error:', error);
					console.log('Response:', xhr.responseText);
					$("#hasil_cari").html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error dalam pencarian! Silakan coba lagi.</div>');
				}
			});
		} else {
			$("#hasil_cari").html('<p class="text-muted">Masukkan ID/Nama produk untuk mencari...</p>');
		}
	}
	
	// Event handlers
	$("#btn-cari").click(function(){
		cariProduk();
	});
	
	$("#cari").keypress(function(e){
		if(e.which == 13) {
			e.preventDefault();
			cariProduk();
		}
	});
	
	$("#cari").on('input', function(){
		var keyword = $(this).val().trim();
		if(keyword.length >= 1) {
			cariProduk();
		} else if(keyword.length == 0) {
			$("#hasil_cari").html('<p class="text-muted">Masukkan ID/Nama produk untuk mencari...</p>');
		}
	});
	
	// Auto focus ke input pencarian
	$("#cari").focus();
	
	// Auto refresh keranjang setelah update
	if(window.location.href.indexOf('success=tambah-data') > -1) {
		setTimeout(function(){
			window.location.href = 'index.php?page=jual#keranjang';
		}, 2000);
	}
});

// Fungsi helper untuk format angka
function number_format(number) {
	return parseInt(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>