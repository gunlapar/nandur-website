<?php
session_start();
require '../Login_page/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../Login_page/login.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$userId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nandur - Dukung Petani Lokal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../jobs/jobs-1.css">
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/x-icon" href="../gambar/nandur_logo.png">
</head>
<body class="bg-green-900 flex justify-center items-center min-h-screen p-4">

<form action="proses.php" method="POST" class="flex flex-col md:flex-row gap-6 max-w-6xl w-full">
  <!-- Kartu Data Diri -->
  <div class="bg-white rounded-lg shadow-md p-6 w-full md:w-1/2">
    <h2 class="text-lg font-bold text-center text-green-900 mb-4">Data Diri</h2>

    <label class="block mb-2 text-sm font-medium">Nama Lengkap</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" class="w-full border p-2 rounded" required>

    <label class="block mt-4 mb-2 text-sm font-medium">Peran</label>
    <input type="text" name="role" value="<?= htmlspecialchars($data['role']) ?>" class="w-full border p-2 rounded" readonly>

    <label class="block mt-4 mb-2 text-sm font-medium">Deskripsi Diri</label>
    <textarea name="deskripsi" rows="4" class="w-full border p-2 rounded"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
  </div>

  <!-- Kartu Data Lahan -->
  <div class="bg-white rounded-lg shadow-md p-6 w-full md:w-1/2">
    <?php if ($data['role'] === 'pemilik_lahan') : ?>
    <h2 class="text-lg font-bold text-center mb-4">Data Lahan</h2>

    <label class="block mb-2 text-sm font-medium">Lahan Yang Dimiliki</label>
    <input type="text" name="lahan_dimiliki" value="<?= htmlspecialchars($data['lahan_dimiliki']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Lahan Padi, Lahan Ganja">

    <label class="block mt-4 mb-2 text-sm font-medium">Jumlah Total Lahan</label>
    <input type="text" name="total_lahan" value="<?= htmlspecialchars($data['total_lahan']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : 20">

    <label class="block mt-4 mb-2 text-sm font-medium">Tipe-Tipe Lahan</label>
    <input type="text" name="tipe_lahan" value="<?= htmlspecialchars($data['tipe_lahan']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Sawah, Kebon, Gurun">

    <label class="block mt-4 mb-2 text-sm font-medium">Durasi Kontrak</label>
    <input type="text" name="durasi_kontrak" value="<?= htmlspecialchars($data['durasi_kontrak']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Minimal 14 Tahun">

    <label class="block mt-4 mb-2 text-sm font-medium">Persebaran Lahan</label>
    <input type="text" name="persebaran" value="<?= htmlspecialchars($data['persebaran']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Kalasan">

    <label class="block mt-4 mb-2 text-sm font-medium">Kontak WhatsApp</label>
    <input type="text" name="whatsapp" value="<?= htmlspecialchars($data['whatsapp']) ?>" class="w-full border p-2 rounded" placeholder="Format: 8xxxxxxx tanpa 0">

  <?php elseif ($data['role'] === 'petani') : ?>
    <h2 class="text-lg font-bold text-center mb-4">Data Petani</h2>

    <label class="block mb-2 text-sm font-medium">Lama Pengalaman Bertani</label>
    <input type="text" name="pengalaman" value="<?= htmlspecialchars($data['pengalaman']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : 5 Tahun">

    <label class="block mt-4 mb-2 text-sm font-medium">Jenis Tanaman Yang Dikuasai</label>
    <input type="text" name="tanaman_dikuasai" value="<?= htmlspecialchars($data['tanaman_dikuasai']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Padi, Jagung">

    <label class="block mt-4 mb-2 text-sm font-medium">Kemampuan Khusus (Pilihan)</label>
    <input type="text" name="kemampuan_khusus" value="<?= htmlspecialchars($data['kemampuan_khusus']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Pengendalian Hama">

    <label class="block mt-4 mb-2 text-sm font-medium">Wilayah Yang Bersedia Digarap</label>
    <input type="text" name="wilayah_digarap" value="<?= htmlspecialchars($data['wilayah_digarap']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Sleman">

    <label class="block mt-4 mb-2 text-sm font-medium">Tim Kerja</label>
    <input type="text" name="tim_kerja" value="<?= htmlspecialchars($data['tim_kerja']) ?>" class="w-full border p-2 rounded" placeholder="Contoh : Mandor A">

    <label class="block mt-4 mb-2 text-sm font-medium">Kontak WhatsApp</label>
    <input type="text" name="whatsapp" value="<?= htmlspecialchars($data['whatsapp']) ?>" class="w-full border p-2 rounded" placeholder="Format: 8xxxxxxx tanpa 0">

  <?php else: ?>
    <p>Detail profil belum tersedia untuk role ini.</p>
  <?php endif; ?>

  <button type="submit" class="mt-6 w-full bg-green-700 hover:bg-green-800 text-white py-2 rounded">
    Simpan Data Lahan / Petani
  </button>
  </div>
</form>

</body>
</html>
