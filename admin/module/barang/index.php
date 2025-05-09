<h4>Data Produk</h4>
<br />
<?php if(isset($_GET['success'])){?>
    <div class="alert alert-success">
        <p>Tambah Data Berhasil !</p>
    </div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
    <div class="alert alert-danger">
        <p>Hapus Data Berhasil !</p>
    </div>
<?php }?>

<!-- Tombol Aksi -->
<button type="button" class="btn btn-primary btn-md mr-2" data-toggle="modal" data-target="#myModal">
    <i class="fa fa-plus"></i> Insert Data
</button>
<a href="index.php?page=produk" class="btn btn-success btn-md">
    <i class="fa fa-refresh"></i> Refresh Data
</a>
<div class="clearfix"></div>
<br />

<!-- Tabel Produk -->
<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm" id="example1">
            <thead>
                <tr style="background:#DFF0D8;color:#333;">
                    <th>No.</th>
                    <th>ID Produk</th>
                    <th>Kategori</th>
                    <th>Nama Produk</th>
                    <th>Harga Jual</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $hasil = $lihat->produk(); // method baru dari model
                    $no = 1;
                    foreach($hasil as $isi) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $isi['id_produk']; ?></td>
                    <td><?= $isi['nama_kategori']; ?></td>
                    <td><?= $isi['nama_produk']; ?></td>
                    <td>Rp.<?= number_format($isi['harga_jual']); ?>,-</td>
                    <td><?= $isi['satuan']; ?></td>
                    <td>
                        <a href="index.php?page=produk/edit&produk=<?= $isi['id_produk']; ?>">
                            <button class="btn btn-warning btn-xs">Edit</button>
                        </a>
                        <a href="fungsi/hapus/hapus.php?produk=hapus&id=<?= $isi['id_produk']; ?>" onclick="return confirm('Hapus data produk ini?');">
                            <button class="btn btn-danger btn-xs">Hapus</button>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:0px;">
            <div class="modal-header" style="background:#285c64;color:#fff;">
                <h5 class="modal-title"><i class="fa fa-plus"></i> Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="fungsi/tambah/tambah.php?produk=tambah" method="POST">
                <div class="modal-body">
                    <table class="table table-striped bordered">
                        <?php $format = $lihat->produk_id(); ?>
                        <tr>
                            <td>ID Produk</td>
                            <td><input type="text" readonly value="<?= $format; ?>" class="form-control" name="id"></td>
                        </tr>
                        <tr>
                            <td>Kategori</td>
                            <td>
                                <select name="kategori" class="form-control" required>
                                    <option value="#">Pilih Kategori</option>
                                    <?php $kat = $lihat->kategori(); foreach($kat as $isi){ ?>
                                    <option value="<?= $isi['id_kategori']; ?>"><?= $isi['nama_kategori']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Produk</td>
                            <td><input type="text" placeholder="Nama Produk" required class="form-control" name="nama"></td>
                        </tr>
                        <tr>
                            <td>Harga Jual</td>
                            <td><input type="number" placeholder="Harga Jual" required class="form-control" name="jual"></td>
                        </tr>
                        <tr>
                            <td>Satuan</td>
                            <td>
                                <select name="satuan" class="form-control" required>
                                    <option value="#">Pilih Satuan</option>
                                    <option value="Cup">Cup</option>
                                    <option value="Porsi">Porsi</option>
                                    <option value="Botol">Botol</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Input</td>
                            <td><input type="text" readonly class="form-control" value="<?= date("j F Y, G:i"); ?>" name="tgl"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Insert Data</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
