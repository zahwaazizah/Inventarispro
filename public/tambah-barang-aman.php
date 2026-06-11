<?php
// File: tambah-barang.php
session_start();

$host = '127.0.0.1';
$dbname = 'sisqrcode';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_inventaris = trim($_POST['kode_inventaris'] ?? '');
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $lokasi_id = (int)($_POST['lokasi_id'] ?? 0);
    $merk = $_POST['merk'] ?? null;
    $serial_number = $_POST['serial_number'] ?? null;
    $spesifikasi = $_POST['spesifikasi'] ?? null;
    $stok = (int)($_POST['stok'] ?? 1);
    $status_barang = $_POST['status_barang'] ?? 'tersedia';
    $kondisi_barang = $_POST['kondisi_barang'] ?? 'baik';
    $tahun_pembelian = !empty($_POST['tahun_pembelian']) ? (int)$_POST['tahun_pembelian'] : null;
    $harga_pembelian = !empty($_POST['harga_pembelian']) ? (float)$_POST['harga_pembelian'] : null;

    if (empty($kode_inventaris) || empty($nama_barang) || $kategori_id == 0 || $lokasi_id == 0) {
        $error = 'Harap isi kode inventaris, nama barang, kategori, dan lokasi.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM barangs WHERE kode_inventaris = ?");
        $stmt->execute([$kode_inventaris]);

        if ($stmt->fetchColumn() > 0) {
            $error = 'Kode inventaris sudah ada.';
        } else {
            $foto = null;

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $target_dir = __DIR__ . '/uploads/barang/';

                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                    $foto = 'uploads/barang/' . $filename;
                }
            }

            try {
                $sql = "INSERT INTO barangs 
                        (kode_inventaris, nama_barang, kategori_id, lokasi_id, merk, serial_number, spesifikasi, stok, status_barang, kondisi_barang, tahun_pembelian, harga_pembelian, foto, created_at, updated_at)
                        VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $kode_inventaris,
                    $nama_barang,
                    $kategori_id,
                    $lokasi_id,
                    $merk,
                    $serial_number,
                    $spesifikasi,
                    $stok,
                    $status_barang,
                    $kondisi_barang,
                    $tahun_pembelian,
                    $harga_pembelian,
                    $foto
                ]);

                $success = 'Barang berhasil ditambahkan!';
                $_POST = [];
            } catch (PDOException $e) {
                $error = 'Gagal menyimpan: ' . $e->getMessage();
            }
        }
    }
}

$kategoris = $pdo->query("SELECT id, nama_kategori FROM kategoris ORDER BY nama_kategori")->fetchAll(PDO::FETCH_OBJ);
$lokasis = $pdo->query("SELECT id, nama_lokasi FROM lokasis ORDER BY nama_lokasi")->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang - InventarisPro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: #f8fafc;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .card-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-secondary,
        .btn-light {
            padding: 12px 24px;
            border-radius: 10px;
        }

        label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }

        .required {
            color: #ef4444;
        }
    </style>
</head>

<body>
<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">
                <i class="fas fa-plus-circle"></i> Tambah Barang Inventaris
            </h3>

            <a href="/inventarispro/public/inventaris" class="btn btn-light btn-sm mt-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card-body p-4">
            <?php if ($success): ?>
                <div class="alert alert-success rounded-3">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger rounded-3">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Kode Inventaris <span class="required">*</span></label>
                        <input type="text" name="kode_inventaris" class="form-control" required
                               value="<?= htmlspecialchars($_POST['kode_inventaris'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nama Barang <span class="required">*</span></label>
                        <input type="text" name="nama_barang" class="form-control" required
                               value="<?= htmlspecialchars($_POST['nama_barang'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Kategori <span class="required">*</span></label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategoris as $k): ?>
                                <option value="<?= $k->id ?>" <?= (($_POST['kategori_id'] ?? 0) == $k->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k->nama_kategori) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Lokasi <span class="required">*</span></label>
                        <select name="lokasi_id" class="form-select" required>
                            <option value="">-- Pilih Lokasi --</option>
                            <?php foreach ($lokasis as $l): ?>
                                <option value="<?= $l->id ?>" <?= (($_POST['lokasi_id'] ?? 0) == $l->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($l->nama_lokasi) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Merk</label>
                        <input type="text" name="merk" class="form-control"
                               value="<?= htmlspecialchars($_POST['merk'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Serial Number</label>
                        <input type="text" name="serial_number" class="form-control"
                               value="<?= htmlspecialchars($_POST['serial_number'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Spesifikasi</label>
                    <textarea name="spesifikasi" class="form-control" rows="3"><?= htmlspecialchars($_POST['spesifikasi'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control"
                               value="<?= htmlspecialchars($_POST['stok'] ?? 1) ?>" min="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Tahun Pembelian</label>
                        <input type="number" name="tahun_pembelian" class="form-control"
                               value="<?= htmlspecialchars($_POST['tahun_pembelian'] ?? '') ?>"
                               placeholder="2026">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Harga Pembelian (Rp)</label>
                        <input type="number" name="harga_pembelian" class="form-control" step="1000"
                               value="<?= htmlspecialchars($_POST['harga_pembelian'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Status Barang</label>
                        <select name="status_barang" class="form-select">
                            <option value="tersedia" <?= (($_POST['status_barang'] ?? '') == 'tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="maintenance" <?= (($_POST['status_barang'] ?? '') == 'maintenance') ? 'selected' : '' ?>>Maintenance</option>
                            <option value="rusak" <?= (($_POST['status_barang'] ?? '') == 'rusak') ? 'selected' : '' ?>>Rusak</option>
                            <option value="hilang" <?= (($_POST['status_barang'] ?? '') == 'hilang') ? 'selected' : '' ?>>Hilang</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Kondisi Barang</label>
                        <select name="kondisi_barang" class="form-select">
                            <option value="baik" <?= (($_POST['kondisi_barang'] ?? '') == 'baik') ? 'selected' : '' ?>>Baik</option>
                            <option value="rusak ringan" <?= (($_POST['kondisi_barang'] ?? '') == 'rusak ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
                            <option value="rusak berat" <?= (($_POST['kondisi_barang'] ?? '') == 'rusak berat') ? 'selected' : '' ?>>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Foto Barang</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Barang
                    </button>

                    <button type="reset" class="btn btn-secondary ms-2">
                        <i class="fas fa-undo-alt"></i> Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>