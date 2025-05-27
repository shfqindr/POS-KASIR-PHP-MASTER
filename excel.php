<?php 
	@ob_start();
	session_start();
	if(!empty($_SESSION['admin'])){ }else{
		echo '<script>window.location="login.php";</script>';
        exit;
	}
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=data-laporan-penjualan-".date('Y-m-d').".xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false); 

    require 'config.php';

    $bulan_tes = array(
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

    // Query untuk mengambil data penjualan dari tabel nota dengan JOIN ke produk dan member
    $sql = "SELECT n.*, p.nama_produk, p.harga_jual, m.nama_member 
            FROM nota n 
            JOIN produk p ON n.id_produk = p.id_produk 
            JOIN member m ON n.id_member = m.id_member";
    
    // Filter berdasarkan parameter
    if(!empty(htmlentities($_GET['cari']))){
        $periode = htmlentities($_GET['bln']).'-'.htmlentities($_GET['thn']);
        $sql .= " WHERE n.periode = ?";
        $params = array($periode);
    } elseif(!empty(htmlentities($_GET['hari']))){
        $hari = htmlentities($_GET['tgl']);
        $sql .= " WHERE DATE(n.tanggal_input) = ?";
        $params = array($hari);
    } else {
        $params = array();
    }
    
    $sql .= " ORDER BY n.tanggal_input DESC";
    
    $row = $config->prepare($sql);
    $row->execute($params);
    $hasil = $row->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
</head>
<body>
    <div class="modal-view">
        <h3 style="text-align:center;"> 
                <?php if(!empty(htmlentities($_GET['cari']))){ ?>
                    Data Laporan Penjualan <?= $bulan_tes[htmlentities($_GET['bln'])];?> <?= htmlentities($_GET['thn']);?>
                <?php }elseif(!empty(htmlentities($_GET['hari']))){?>
                    Data Laporan Penjualan <?= htmlentities($_GET['tgl']);?>
                <?php }else{?>
                    Data Laporan Penjualan <?= $bulan_tes[date('m')];?> <?= date('Y');?>
                <?php }?>
        </h3>
        <table border="1" width="100%" cellpadding="3" cellspacing="0">
            <thead>
                <tr bgcolor="yellow">
                    <th>No</th>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Kasir</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no = 1;
                    $total_jumlah = 0;
                    $total_penjualan = 0;
                    
                    foreach($hasil as $isi): 
                        // Hitung total per item
                        $total_item = $isi['harga_jual'] * $isi['jumlah'];
                        $total_jumlah += $isi['jumlah'];
                        $total_penjualan += $total_item;
                ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo htmlentities($isi['id_produk']);?></td>
                    <td><?php echo htmlentities($isi['nama_produk']);?></td>
                    <td>Rp. <?php echo number_format($isi['harga_jual']);?>,-</td>
                    <td><?php echo $isi['jumlah'];?></td>
                    <td>Rp. <?php echo number_format($total_item);?>,-</td>
                    <td><?php echo htmlentities($isi['nama_member']);?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($isi['tanggal_input']));?></td>
                </tr>
                <?php 
                    $no++; 
                    endforeach;
                ?>
                
                <!-- Baris Total -->
                <tr bgcolor="lightgray">
                    <td colspan="4" style="text-align:center;"><strong>TOTAL KESELURUHAN</strong></td>
                    <td><strong><?php echo $total_jumlah;?> Item</strong></td>
                    <td><strong>Rp. <?php echo number_format($total_penjualan);?>,-</strong></td>
                    <td colspan="2"><strong>Total Transaksi: <?php echo $no-1;?></strong></td>
                </tr>
                
                <!-- Baris Statistik -->
                <?php if($no > 1): ?>
                <tr bgcolor="lightblue">
                    <td colspan="8" style="text-align:center;">
                        <strong>
                            Rata-rata per Transaksi: Rp. <?php echo number_format($total_penjualan/($no-1));?>,-
                            | Rata-rata Item per Transaksi: <?php echo round($total_jumlah/($no-1), 2);?>
                        </strong>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <br>
        <p style="text-align:center;">
            <strong>Laporan digenerate pada: <?php echo date('d F Y, H:i:s');?></strong><br>
            <em>Sistem POS - Point of Sale</em>
        </p>
    </div>
</body>
</html>
