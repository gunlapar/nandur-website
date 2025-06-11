<?php
// includes/upload_helper.php

/**
 * Handle generic upload
 *
 * @param array $file
 * @param string $uploadDir
 * @return array [success => bool, filename|error => string]
 */
function handle_upload(array $file, string $uploadDir): array {
    // 1. Cek error upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error code ' . $file['error']];
    }

    // 2. Validasi ekstensi
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        return ['success' => false, 'error' => 'Ekstensi tidak diizinkan.'];
    }

    // 3. Validasi mime type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (strpos($mime, 'image/') !== 0) {
        return ['success' => false, 'error' => 'File bukan gambar valid.'];
    }

    // 4. Generate nama unik
    $uniqueName = bin2hex(random_bytes(8)) . '.' . $ext;

    // 5. Pastikan folder ada - Perbaikan pesan error
    if (!is_dir($uploadDir)) {
        // Tambahkan logging untuk membantu diagnosa
        error_log("Mencoba membuat direktori: $uploadDir");
        
        // Cek parent directory apakah writable
        $parentDir = dirname($uploadDir);
        if (!is_writable($parentDir)) {
            error_log("Parent directory ($parentDir) tidak writable");
            return ['success' => false, 'error' => 'Parent direktori tidak writable. Hubungi administrator.'];
        }
        
        // Coba buat direktori dengan error handling lebih baik
        if (!@mkdir($uploadDir, 0755, true)) {
            $error = error_get_last();
            error_log("Gagal membuat direktori: " . ($error['message'] ?? 'Alasan tidak diketahui'));
            return ['success' => false, 'error' => 'Gagal membuat folder upload. Cek permission.'];
        }
    } else if (!is_writable($uploadDir)) {
        // Cek jika direktori sudah ada tapi tidak writable
        error_log("Direktori upload ($uploadDir) tidak writable");
        return ['success' => false, 'error' => 'Direktori upload tidak writable. Hubungi administrator.'];
    }

    // 6. Pindahkan file
    $dest = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uniqueName;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        error_log("Gagal memindahkan file dari {$file['tmp_name']} ke $dest");
        return ['success' => false, 'error' => 'Gagal memindahkan file.'];
    }

    return ['success' => true, 'filename' => $uniqueName];
}

/**
 * Handle khusus avatar upload
 *
 * @param array $file
 * @return array
 */
function upload_avatar(array $file): array {
    // Pastikan file diupload
    if (empty($file) || empty($file['tmp_name'])) {
        return ['success' => false, 'error' => 'Tidak ada file yang diupload.'];
    }
    
    // Tentukan direktori upload dengan path yang lebih tepat
    $uploadDir = __DIR__ . '/../uploads/avatars';
    
    // Log untuk debugging
    error_log("Upload avatar ke direktori: $uploadDir");
    
    return handle_upload($file, $uploadDir);
}