<?php
class view
{
    protected $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Fungsi untuk memverifikasi apakah yang login adalah admin
    public function is_admin()
    {
        return isset($_SESSION['admin']) && $_SESSION['admin']['role'] == 'admin';  // Sesuaikan dengan session admin yang ada
    }

    public function member()
    {
        // Pastikan hanya admin yang bisa melihat member
        if ($this->is_admin()) {
            $sql = "SELECT member.*, login.*
                    FROM member 
                    INNER JOIN login ON member.id_member = login.id_member";
            $row = $this->db->prepare($sql);
            $row->execute();
            return $row->fetchAll();
        } else {
            return null; // Atau bisa dikembalikan dengan error jika perlu
        }
    }

    public function member_edit($id)
    {
        // Hanya admin yang bisa mengedit member
        if ($this->is_admin()) {
            $sql = "SELECT member.*, login.*
                    FROM member 
                    INNER JOIN login ON member.id_member = login.id_member
                    WHERE member.id_member = ?";
            $row = $this->db->prepare($sql);
            $row->execute([$id]);
            return $row->fetch();
        } else {
            return null;
        }
    }

    public function produk()
    {
        $sql = "SELECT * FROM produk ORDER BY id_produk DESC";
        $row = $this->db->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    public function produk_cari($cari)
    {
        $sql = "SELECT * FROM produk
                WHERE id_produk LIKE ? OR nama_produk LIKE ?";
        $like = "%$cari%";
        $row = $this->db->prepare($sql);
        $row->execute([$like, $like]);
        return $row->fetchAll();
    }

    public function produk_id()
    {
        $sql = "SELECT id_produk FROM produk ORDER BY id_produk DESC LIMIT 1";
        $row = $this->db->prepare($sql);
        $row->execute();
        $hasil = $row->fetch();

        if ($hasil) {
            $urut = substr($hasil['id_produk'], 2);
            $tambah = (int)$urut + 1;
        } else {
            $tambah = 1;
        }

        $format = 'PR' . str_pad($tambah, 3, '0', STR_PAD_LEFT);
        return $format;
    }

    public function nota()
    {
        $sql = "SELECT nota.*, produk.nama_produk, member.nama_member
                FROM nota 
                LEFT JOIN produk ON produk.id_produk = nota.id_produk 
                LEFT JOIN member ON member.id_member = nota.id_member 
                ORDER BY id_nota DESC";
        $row = $this->db->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    public function hari_jual($hari)
    {
        $param = "%$hari%";
        $sql = "SELECT nota.*, produk.nama_produk, member.nama_member
                FROM nota 
                LEFT JOIN produk ON produk.id_produk = nota.id_produk 
                LEFT JOIN member ON member.id_member = nota.id_member 
                WHERE nota.tanggal_input LIKE ?
                ORDER BY id_nota ASC";
        $row = $this->db->prepare($sql);
        $row->execute([$param]);
        return $row->fetchAll();
    }

    public function penjualan()
    {
        $sql = "SELECT penjualan.*, produk.nama_produk, member.nama_member
                FROM penjualan
                LEFT JOIN produk ON produk.id_produk = penjualan.id_produk
                LEFT JOIN member ON member.id_member = penjualan.id_member
                ORDER BY penjualan.id_produk ASC";
        $row = $this->db->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    public function jumlah_total_nota()
    {
        $sql = "SELECT SUM(total) AS total_bayar FROM nota";
        $row = $this->db->prepare($sql);
        $row->execute();
        return $row->fetch();
    }

    // Toko hanya bisa diakses oleh admin
    public function toko()
    {
        if ($this->is_admin()) {
            $sql = "SELECT * FROM toko";
            $row = $this->db->prepare($sql);
            $row->execute();
            return $row->fetchAll();
        } else {
            return null; // Atau beri pesan error
        }
    }
}
?>
