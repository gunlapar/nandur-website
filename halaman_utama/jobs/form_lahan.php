<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Form Input Data Lahan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen font-sans p-4">

  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-4xl">
    <h2 class="text-2xl font-semibold text-green-700 text-center mb-6">Form Nandur Lahan</h2>

    <?php if (isset($_GET['sukses'])): ?>
      <div class="mb-4 p-3 text-green-800 bg-green-100 rounded shadow text-center">
        Data berhasil disimpan!
      </div>
    <?php endif; ?>

    <form action="proses_lahan.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-green-700 font-medium mb-1">Nama Pemilik:</label>
        <input type="text" name="pemilik" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Tanggal:</label>
        <input type="date" name="tanggal" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div class="md:col-span-2">
        <label class="block text-green-700 font-medium mb-1">Deskripsi Singkat:</label>
        <textarea name="deskripsi_singkat" rows="2" maxlength="120" required
          class="w-full px-4 py-2 border border-green-200 rounded-md resize-none"
          placeholder="Maks 20 kata"></textarea>
      </div>

      <div class="md:col-span-2">
        <label class="block text-green-700 font-medium mb-1">Deskripsi Rinci:</label>
        <textarea name="deskripsi_rinci" rows="4" required
          class="w-full px-4 py-2 border border-green-200 rounded-md resize-none"></textarea>
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Gaji Petani (Rp):</label>
        <input type="number" name="harga" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Status:</label>
        <select name="status" required class="w-full px-4 py-2 border border-green-200 rounded-md">
          <option value="">-- Pilih Status --</option>
          <option value="tersedia">Tersedia</option>
          <option value="terjual">Terjual</option>
        </select>
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Lokasi:</label>
        <input type="text" name="lokasi" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Jenis Lahan:</label>
        <input type="text" name="jenis_lahan" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Jenis Tanaman:</label>
        <input type="text" name="jenis_tanaman" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Luas Lahan (m²):</label>
        <input type="number" name="luas_lahan" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div>
        <label class="block text-green-700 font-medium mb-1">Kontak:</label>
        <input type="number" name="kontak" required class="w-full px-4 py-2 border border-green-200 rounded-md" />
      </div>

      <div class="md:col-span-2">
        <label class="block text-green-700 font-medium mb-1">Ketentuan Bertani:</label>
        <textarea name="ketentuan_bertani" rows="3" required
          class="w-full px-4 py-2 border border-green-200 rounded-md resize-none"></textarea>
      </div>

      <div class="md:col-span-2">
        <button type="submit" class="w-full bg-green-700 text-white py-3 rounded-md hover:bg-green-800 transition">
          Simpan Data
        </button>
      </div>
    </form>
  </div>
</body>
</html>
