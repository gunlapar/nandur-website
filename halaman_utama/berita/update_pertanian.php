<?php
/**
 * Script untuk update berita secara manual atau via cron job
 */

echo "=== UPDATE BERITA PERTANIAN ===\n";
echo "Waktu: " . date('Y-m-d H:i:s') . "\n\n";

// Jalankan pengambil berita
include 'berita_pertanian.php';

echo "\n=== UPDATE SELESAI ===\n";
echo "Silakan refresh halaman website untuk melihat berita terbaru.\n";
?>