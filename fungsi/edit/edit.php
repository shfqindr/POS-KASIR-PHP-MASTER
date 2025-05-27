<?php
session_start();
if (empty($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit;
}

require '../../config.php';

// ==============================================
// HANDLE UPDATE PENGATURAN TOKO
// ==============================================
if (!empty($_GET['pengaturan']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        htmlentities($_POST['namatoko']),
        htmlentities($_POST['alamat']),
        htmlentities($_POST['kontak']),
        htmlentities($_POST['pemilik']),
        '1'
    ];
    
    $sql = 'UPDATE toko SET nama_toko=?, alamat_toko=?, tlp=?, nama_pemilik=? WHERE id_toko = ?';
    $config->prepare($sql)->execute($data);
    header("Location: ../../index.php?page=pengaturan&success=edit-data");
    exit;
}

// ==============================================
// HANDLE UPDATE KATEGORI
// ==============================================
if (!empty($_GET['kategori']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        htmlentities($_POST['kategori']),
        htmlentities($_POST['id'])
    ];
    
    $sql = 'UPDATE kategori SET nama_kategori=? WHERE id_kategori=?';
    $config->prepare($sql)->execute($data);
    header("Location: ../../index.php?page=kategori&success-edit=edit-data");
    exit;
}

// ==============================================
// HANDLE UPDATE PRODUK
// ==============================================
if (isset($_GET['produk']) && $_GET['produk'] === 'edit') {
    try {
        // TAMPILKAN FORM EDIT - GET REQUEST
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
                throw new Exception("ID produk tidak valid!");
            }
            
            $id = trim($_GET['id']);
            $stmt = $config->prepare("SELECT * FROM produk WHERE id_produk = ?");
            $stmt->execute([$id]);
            $produk = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$produk) {
                throw new Exception("Produk tidak ditemukan!");
            }

            // TAMPILKAN FORM HTML
            ?>
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Edit Produk</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
            </head>
            <body class="bg-light">
                <div class="container py-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning">
                            <h3 class="mb-0">Edit Produk</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($produk['id_produk']) ?>">
                                
                                <div class="form-group">
                                    <label>ID Produk</label>
                                    <input type="text" class="form-control" 
                                           value="<?= htmlspecialchars($produk['id_produk']) ?>" 
                                           disabled>
                                </div>
                                
                                <div class="form-group">
                                    <label>Harga Jual</label>
                                    <input type="number" class="form-control" 
                                           name="jual" 
                                           value="<?= htmlspecialchars($produk['harga_jual']) ?>" 
                                           step="100" 
                                           required>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="../../index.php?page=produk" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
            exit;
        }

        // PROSES UPDATE - POST REQUEST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id']) || !isset($_POST['jual'])) {
                throw new Exception("Data tidak lengkap!");
            }

            $data = [
                (float)$_POST['jual'],
                trim($_POST['id'])
            ];

            $sql = "UPDATE produk SET harga_jual = ? WHERE id_produk = ?";
            $config->prepare($sql)->execute($data);
            
            // Redirect kembali ke halaman produk yang sama
            header("Location: ../../index.php?page=produk&success=edit");
            exit;
        }

    } catch(Exception $e) {
        echo '<div class="container mt-5"><div class="alert alert-danger">'
            . $e->getMessage() 
            . ' <a href="../../index.php?page=produk" class="btn btn-secondary ml-2">Kembali</a></div></div>';
        exit;
    }
}

// ==============================================
// HANDLE UPDATE LAINNYA (TANPA PERUBAHAN)
// ==============================================
// [Kode untuk update gambar member, profil, password, dan penjualan tetap sama di sini]

http_response_code(404);
echo "Halaman tidak ditemukan";

    // Update gambar member
    if (!empty($_GET['gambar'])) {
        $id = htmlentities($_POST['id']);
        set_time_limit(0);
        $allowedImageType = ["image/gif", "image/JPG", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", 'image/webp'];
        $filepath = $_FILES['foto']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);
        $allowedTypes = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            'image/jpg' => 'jpeg',
            'image/webp' => 'webp'
        ];

        if (!in_array($filetype, array_keys($allowedTypes))) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        } elseif ($_FILES['foto']["error"] > 0) {
            echo '<script>alert("Error uploading file.");window.location="../../index.php?page=user"</script>';
            exit;
        } elseif (!in_array($_FILES['foto']["type"], $allowedImageType)) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        } elseif (round($_FILES['foto']["size"] / 1024) > 4096) {
            echo '<script>alert("WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB");window.location="../../index.php?page=user"</script>';
            exit;
        } else {
            $dir = '../../assets/img/user/';
            $tmp_name = $_FILES['foto']['tmp_name'];
            $name = time() . basename($_FILES['foto']['name']);
            if (move_uploaded_file($tmp_name, $dir . $name)) {
                $foto2 = $_POST['foto2'];
                unlink('../../assets/img/user/' . $foto2);
                $data = [$name, $id];
                $sql = 'UPDATE member SET gambar=? WHERE id_member=?';
                $row = $config->prepare($sql);
                $row->execute($data);
                echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
            } else {
                echo '<script>alert("Masukan Gambar !");window.location="../../index.php?page=user"</script>';
                exit;
            }
        }
    }

    // Update profil member
    if (!empty($_GET['profil'])) {
        $id = htmlentities($_POST['id']);
        $nama = htmlentities($_POST['nama']);
        $alamat = htmlentities($_POST['alamat']);
        $tlp = htmlentities($_POST['tlp']);
        $email = htmlentities($_POST['email']);
        $nik = htmlentities($_POST['nik']);

        $data = [$nama, $alamat, $tlp, $email, $nik, $id];
        $sql = 'UPDATE member SET nm_member=?, alamat_member=?, telepon=?, email=?, NIK=? WHERE id_member=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }

    // Update password member
    if (!empty($_GET['pass'])) {
        $id = htmlentities($_POST['id']);
        $user = htmlentities($_POST['user']);
        $pass = htmlentities($_POST['pass']);

        $data = [$user, md5($pass), $id];
        $sql = 'UPDATE login SET user=?, pass=? WHERE id_member=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }

    // Update penjualan
    if (!empty($_GET['jual'])) {
        $id = htmlentities($_POST['id']);
        $id_barang = htmlentities($_POST['id_barang']);
        $jumlah = htmlentities($_POST['jumlah']);

        $sql_tampil = "SELECT * FROM barang WHERE id_barang=?";
        $row_tampil = $config->prepare($sql_tampil);
        $row_tampil->execute([$id_barang]);
        $hasil = $row_tampil->fetch();

        if ($hasil['stok'] >= $jumlah) {
            $jual = $hasil['harga_jual'];
            $total = $jual * $jumlah;
            $data1 = [$jumlah, $total, $id];
            $sql1 = 'UPDATE penjualan SET jumlah=?, total=? WHERE id_penjualan=?';
            $row1 = $config->prepare($sql1);
            $row1->execute($data1);
            echo '<script>window.location="../../index.php?page=jual#keranjang"</script>';
        } else {
            echo '<script>alert("Keranjang Melebihi Stok Barang Anda !"); window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }

    // Cari barang
    if (!empty($_GET['cari_barang'])) {
        $cari = trim(strip_tags($_POST['keyword']));
        if ($cari != '') {
            $sql = "SELECT barang.*, kategori.id_kategori, kategori.nama_kategori
                    FROM barang 
                    INNER JOIN kategori ON barang.id_kategori = kategori.id_kategori
                    WHERE barang.id_barang LIKE ? OR barang.nama_barang LIKE ? OR barang.merk LIKE ?";
            $row = $config->prepare($sql);
            $row->execute(['%' . $cari . '%', '%' . $cari . '%', '%' . $cari . '%']);
            $hasil1 = $row->fetchAll();

            // Tampilkan hasil pencarian
            ?>
            <table class="table table-striped" width="100%" id="example2">
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            <?php foreach ($hasil1 as $hasil): ?>
                <tr>
                    <td><?= htmlspecialchars($hasil['id_barang']); ?></td>
                    <td><?= htmlspecialchars($hasil['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($hasil['merk']); ?></td>
                    <td>Rp. <?= number_format($hasil['harga_jual'], 0, ',', '.'); ?>,-</td>
                    <td>
                        <a href="fungsi/tambah/tambah.php?jual=jual&id=<?= urlencode($hasil['id_barang']); ?>&id_kasir=<?= $_SESSION['admin']['id_member']; ?>" class="btn btn-success">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
            <?php
        }
    }
?>
