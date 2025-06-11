<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Berita Pertanian</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
</head>
<body class="bg-white">

<?php
// Ambil cache berita pertanian
$cache_file = 'cache/all_agriculture_news.json';

if (file_exists($cache_file)) {
    $agriculture_news = json_decode(file_get_contents($cache_file), true);
} else {
    // Jika belum ada cache, jalankan pengambil berita
    include 'berita_pertanian.php';
}

// Fungsi untuk memformat tanggal ke Bahasa Indonesia
function formatTanggalIndonesia($date_string) {
    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    
    $timestamp = strtotime($date_string);
    $tanggal = date('d F Y', $timestamp);
    
    foreach ($bulan as $eng => $ind) {
        $tanggal = str_replace($eng, $ind, $tanggal);
    }
    
    return $tanggal;
}
?>

<section class="bg-white">
    <div class="container px-5 py-24 mx-auto">
        <!-- Judul Halaman -->
        <div class="text-center mb-16">
            <h2 style="font-family: 'Archivo Black', sans-serif;" class="text-4xl font-bold text-green-900 mb-4">
                Berita Pertanian Terkini
            </h2>
            <div class="w-24 h-1 bg-green-600 mx-auto"></div>
        </div>
        
        <?php if (empty($agriculture_news)): ?>
            <div class="text-center py-12">
                <p class="text-xl text-gray-600">Tidak ada berita pertanian yang tersedia saat ini.</p>
            </div>
        <?php else: ?>
            <div class="flex flex-col flex-wrap -m-4">
                <?php foreach ($agriculture_news as $index => $berita): ?>
                    <div class="p-4">
                        <a href="<?php echo htmlspecialchars($berita['link']); ?>" target="_blank" class="block hover:opacity-90 transition-opacity duration-300">
                            <div class="flex md:flex-row flex-col border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                
                                <!-- Area Gambar -->
                                <div class="w-full md:w-1/3 p-4 md:p-8 flex items-center justify-center bg-gray-50">
                                    <?php if (!empty($berita['image'])): ?>
                                        <img class="h-full w-full object-cover rounded-3xl shadow-md" 
                                             src="<?php echo htmlspecialchars($berita['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($berita['title']); ?>"
                                             onerror="this.src='https://via.placeholder.com/400x300/22c55e/ffffff?text=Berita+Pertanian'">
                                    <?php else: ?>
                                        <img class="h-full w-full object-cover rounded-3xl shadow-md" 
                                             src="https://i.pinimg.com/736x/64/4a/81/644a8126dc038ff6f1d29b3f208bce72.jpg" 
                                             alt="Placeholder Berita Pertanian">
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Area Konten -->
                                <div class="w-full md:w-2/3 p-8">
                                    <!-- Judul Berita -->
                                    <h1 style="font-family: 'Archivo Black', sans-serif;" 
                                       class="text-2xl md:text-3xl font-semibold mb-5 text-green-900 hover:text-green-700 transition-colors duration-300">
                                        <?php echo htmlspecialchars($berita['title']); ?>
                                    </h1>
                                    
                                    <!-- Deskripsi -->
                                    <p class="text-lg leading-relaxed text-gray-700 mb-4">
                                        <?php echo htmlspecialchars(substr($berita['description'], 0, 200)) . '...'; ?>
                                    </p>
                                    
                                    <!-- Meta Information -->
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-sm text-gray-500 space-y-2 sm:space-y-0">
                                        <span class="font-medium">
                                            📅 <?php echo formatTanggalIndonesia($berita['pubDate']); ?>
                                        </span>
                                        <span class="font-medium">
                                            🌐 Sumber: <?php echo htmlspecialchars($berita['source']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <?php if ($index >= 8) break; // Batasi tampilan hingga 9 berita ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>