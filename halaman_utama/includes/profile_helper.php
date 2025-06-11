<?php
// includes/profile_helper.php

/**
 * Simpan data profil ke tabel yang sesuai role
 *
 * @param mysqli $conn
 * @param string $role       'petani' atau 'pemilik_lahan'
 * @param int    $user_id
 * @param array  $data       semua field dari form, sudah divalidasi
 * @return bool
 */
function save_profile(mysqli $conn, string $role, int $user_id, array $data): bool {
    // Definisi tabel & field per role
    $config = [
// untuk pemilik_lahan, tanpa tim_kerja
'pemilik_lahan' => [
  'table'  => 'profil_pemilik_lahan',
  'fields' => [
    'nama'=>'s','deskripsi'=>'s',
    'jenis_lahan'=>'s','jumlah_lahan'=>'i',
    'tipe_lahan'=>'s','durasi'=>'s',
    'lokasi'=>'s','kontak'=>'s','avatar'=>'s'
  ],
],
// untuk petani, dengan tim_kerja
'petani' => [
  'table'  => 'profil_petani',
  'fields' => [
    'nama'=>'s','deskripsi'=>'s','pengalaman_bertani'=>'s',
    'tanaman_dikuasai'=>'s','kemampuan_khusus'=>'s',
    'wilayah_garapan'=>'s','tim_kerja'=>'s','kontak'=>'s','avatar'=>'s'
  ],
],
    ];

    if (!isset($config[$role])) {
        error_log("save_profile: Role tidak valid: $role");
        return false;
    }

    $table      = $config[$role]['table'];
    $fields     = $config[$role]['fields'];
    $names      = array_keys($fields);
    $placeholders = array_fill(0, count($names), '?');
    $sets         = array_map(fn($f) => "`$f` = VALUES(`$f`)", $names);

    // Bangun SQL
    $sql = sprintf(
        "INSERT INTO `%s` (`user_id`, `%s`) VALUES (?, %s)
         ON DUPLICATE KEY UPDATE %s",
        $table,
        implode('`,`', $names),
        implode(', ', $placeholders),
        implode(', ', $sets)
    );

    if (!($stmt = $conn->prepare($sql))) {
        error_log("save_profile prepare gagal ({$table}): " . $conn->error);
        return false;
    }

    // bind types & params: [user_id, insert..., ] 
    $types  = 'i' . implode('', $fields);
    $params = [$user_id];
    foreach ($names as $f) {
        // jika data null/empty dan kolom integer, kirim 0
        $params[] = $fields[$f] === 'i' 
            ? (int)($data[$f] ?? 0)
            : ($data[$f] ?? '');
    }

    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        error_log("save_profile execute gagal: " . $stmt->error);
        return false;
    }
    return true;
}

/**
 * Ambil data profil berdasarkan role
 *
 * @param mysqli $conn
 * @param string $role       'petani' atau 'pemilik_lahan'
 * @param int    $user_id
 * @return array
 */
function load_profile(mysqli $conn, string $role, int $user_id): array {
    // Definisi tabel & field per role (sama dengan save_profile)
    $config = [
        'petani' => [
            'table'  => 'profil_petani',
            'fields' => ['nama','deskripsi','pengalaman_bertani','tanaman_dikuasai',
                         'kemampuan_khusus','wilayah_garapan','tim_kerja','kontak','avatar'],
        ],
        'pemilik_lahan' => [
            'table'  => 'profil_pemilik_lahan',
            'fields' => ['nama','deskripsi','jenis_lahan','jumlah_lahan',
                         'tipe_lahan','durasi','lokasi','kontak','avatar'],
        ],
    ];

    if (!isset($config[$role])) {
        error_log("load_profile: Role tidak valid: $role");
        return [];
    }

    $table  = $config[$role]['table'];
    $fields = $config[$role]['fields'];
    $cols   = implode(', ', $fields);

    $sql = "SELECT $cols FROM `$table` WHERE user_id = ?";
    if (!($stmt = $conn->prepare($sql))) {
        error_log("load_profile prepare gagal ($table): " . $conn->error);
        return [];
    }

    $stmt->bind_param('i', $user_id);
    if (!$stmt->execute()) {
        error_log("load_profile execute gagal: " . $stmt->error);
        return [];
    }

    $res = $stmt->get_result();
    return $res->fetch_assoc() ?: [];
}
