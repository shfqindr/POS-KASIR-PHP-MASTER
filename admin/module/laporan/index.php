<?php 
	$bulan_tes =array(
		'01'=>"Januari",
		'02'=>"Februari",
		'03'=>"Maret",
		'04'=>"April",
		'05'=>"Mei",
		'06'=>"Juni",
		'07'=>"Juli",
		'08'=>"Agustus",
		'09'=>"September",
		'10'=>"Oktober",
		'11'=>"November",
		'12'=>"Desember"
	);
?>
<div class="row">
	<div class="col-md-12">
		<h4>
			<?php if(!empty($_GET['cari'])){ ?>
			Data Laporan Penjualan <?= $bulan_tes[$_POST['bln']];?> <?= $_POST['thn'];?>
			<?php }else{?>
			Data Laporan Penjualan <?= $bulan_tes[date('m')];?> <?= date('Y');?>
			<?php } ?>
		</h4>
		<br />
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mt-2">Cari Laporan Per Bulan</h5>
			</div>
			<div class="card-body p-0">
				<form method="post" action="index.php?page=laporan&cari=ok">
					<table class="table table-striped">
						<tr>
							<th>
								Pilih Bulan
							</th>
							<th>
								Pilih Tahun
							</th>
							<th>
								Aksi
							</th>
						</tr>
						<tr>
							<td>
								<select name="bln" class="form-control">
									<option selected="selected">Bulan</option>
									<?php
										$bulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
										$jlh_bln=count($bulan);
										$bln1 = array('01','02','03','04','05','06','07','08','09','10','11','12');
										for($c=0; $c<$jlh_bln; $c++){
											echo"<option value='$bln1[$c]'> $bulan[$c] </option>";
										}
									?>
								</select>
							</td>
							<td>
								<?php
									$now=date('Y');
									echo "<select name='thn' class='form-control'>";
									echo '<option selected="selected">Tahun</option>';
									for ($a=2017;$a<=$now;$a++)
									{
										echo "<option value='$a'>$a</option>";
									}
									echo "</select>";
								?>
							</td>
							<td>
								<input type="hidden" name="periode" value="ya">
								<button class="btn btn-primary">
									<i class="fa fa-search"></i> Cari
								</button>
								<a href="index.php?page=laporan" class="btn btn-success">
									<i class="fa fa-refresh"></i> Refresh</a>

								<?php if(!empty($_GET['cari'])){?>
								<a href="excel.php?cari=yes&bln=<?=$_POST['bln'];?>&thn=<?=$_POST['thn'];?>"
									class="btn btn-info"><i class="fa fa-download"></i>
									Excel</a>
								<?php }else{?>
								<a href="excel.php" class="btn btn-info"><i class="fa fa-download"></i>
									Excel</a>
								<?php } ?>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
         <br />
         <br />
         <!-- view produk -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered w-100 table-sm" id="example1">
						<thead>
							<tr style="background:#DFF0D8;color:#333;">
								<th> No</th>
								<th> ID Produk</th>
								<th> Nama Produk</th>
								<th style="width:10%;"> Jumlah</th>
								<th style="width:10%;"> Total</th>
								<th> Kasir</th>
								<th> Tanggal Input</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no=1; 
								if(!empty($_GET['cari'])){
									$periode = $_POST['bln'].'-'.$_POST['thn'];
									$hasil = $lihat -> periode_jual($periode);
								}else{
									$hasil = $lihat -> jual();
								}
							?>
							<?php 
								$bayar = 0;
								$jumlah = 0;
								foreach($hasil as $isi){ 
									$bayar += $isi['total'];
									$jumlah += $isi['jumlah'];
							?>
							<tr>
								<td><?php echo $no;?></td>
								<td><?php echo $isi['id_produk'];?></td>
								<td><?php echo $isi['nama_produk'];?></td>
								<td><?php echo $isi['jumlah'];?> </td>
								<td>Rp.<?php echo number_format($isi['total']);?>,-</td>
								<td><?php echo $isi['nama_member'];?></td>
								<td><?php echo $isi['tanggal_input'];?></td>
							</tr>
							<?php $no++; }?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3">Total Terjual</td>
								<th><?php echo $jumlah;?></td>
								<th>Rp.<?php echo number_format($bayar);?>,-</th>
								<th style="background:#0bb365;color:#fff;">Keuntungan</th>
								<th style="background:#0bb365;color:#fff;">
									Rp.<?php echo number_format($bayar);?>,-</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
     </div>
 </div>
