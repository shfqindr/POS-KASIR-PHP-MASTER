<?php
$hasil = $lihat->produk();
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Notifikasi -->
        <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= match($_GET['success']) {
                'tambah' => 'Data produk berhasil ditambahkan!',
                'edit' => 'Data produk berhasil diperbarui!',
                default => 'Operasi berhasil!'
            } ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <!-- Header dan Tombol -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Daftar Produk</h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>

        <!-- Tabel -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0" id="tabelProduk">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>ID Produk</th>
                            <th>Kategori</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($hasil as $produk): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($produk['id_produk']) ?></td>
                            <td><?= htmlspecialchars($produk['nama_kategori']) ?></td>
                            <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
                            <td>Rp <?= number_format($produk['harga_jual'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($produk['satuan']) ?></td>
                            <td>
                                <a href="fungsi/edit/edit.php?produk=edit&id=<?= urlencode($produk['id_produk']) ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="fungsi/hapus/hapus.php?produk=hapus&id=<?= urlencode($produk['id_produk']) ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Produk Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="fungsi/tambah/tambah.php?produk=tambah" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>ID Produk</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($lihat->produk_id()) ?>" 
                                   name="id" 
                                   readonly>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach($lihat->kategori() as $kategori): ?>
                                <option value="<?= htmlspecialchars($kategori['id_kategori']) ?>">
                                    <?= htmlspecialchars($kategori['nama_kategori']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="nama" 
                                   required 
                                   placeholder="Masukkan nama produk">
                        </div>
                        <div class="form-group">
                            <label>Harga Jual</label>
                            <input type="number" 
                                   class="form-control" 
                                   name="jual" 
                                   min="0" 
                                   required 
                                   placeholder="Masukkan harga">
                        </div>
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="satuan" 
                                   required 
                                   placeholder="Contoh: pcs, kg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#tabelProduk').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            }
        });
    });
    </script>
</body>
</html>
