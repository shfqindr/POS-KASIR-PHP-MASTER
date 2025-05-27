<h3>Dashboard</h3>
<br/>
<?php 
    // Menghitung jumlah penjualan
    $sql = "SELECT SUM(jumlah) AS total_terjual FROM penjualan";
    $row = $config->prepare($sql);
    $row->execute();
    $result_terjual = $row->fetch(PDO::FETCH_ASSOC);
    $total_terjual = $result_terjual['total_terjual'];

    // Menghitung jumlah produk
    $sql = "SELECT COUNT(id_produk) AS total_produk FROM produk";
    $row = $config->prepare($sql);
    $row->execute();
    $result_produk = $row->fetch(PDO::FETCH_ASSOC);
    $total_produk = $result_produk['total_produk'];
?>

<div class="row">
    <!-- STATUS cardS -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="pt-2"><i class="fas fa-chart-bar"></i> Total Penjualan</h6>
            </div>
            <div class="card-body">
                <center>
                    <h1><?php echo number_format($total_terjual); ?></h1>
                </center>
            </div>
            <div class="card-footer">
                <a href='index.php?page=penjualan'>Tabel Penjualan <i class='fa fa-angle-double-right'></i></a>
            </div>
        </div>
        <!--/grey-card -->
    </div><!-- /col-md-3-->

    <!-- STATUS cardS -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="pt-2"><i class="fas fa-bookmark"></i> Total Produk</h6>
            </div>
            <div class="card-body">
                <center>
                    <h1><?php echo number_format($total_produk); ?></h1>
                </center>
            </div>
            <div class="card-footer">
                <a href='index.php?page=produk'>Tabel Produk <i class='fa fa-angle-double-right'></i></a>
            </div>
        </div>
        <!--/grey-card -->
    </div><!-- /col-md-3-->
</div>
