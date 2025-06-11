<?php
/**
 * Pengambil dan Pemfilter Berita Pertanian
 */

// Daftar sumber feed pertanian
$feed_url = [
    'modernfarmer' => 'https://modernfarmer.com/feed/'
];

// Kata kunci pertanian untuk penyaringan tambahan (opsional)
$agriculture_keywords = [
    'pertanian', 'agriculture', 'farming', 'petani', 'farmer', 'tanaman', 'crop',
    'panen', 'harvest', 'bibit', 'seed', 'pupuk', 'fertilizer', 'irigasi', 'irrigation',
    'pestisida', 'pesticide', 'traktor', 'tractor', 'subsidi', 'subsidy'
];

// Fungsi untuk mengambil dan memfilter feed
function getAgricultureFeed($feed_url, $cache_time = 3600, $keywords = []) {
    $cache_filename = 'cache/agri_' . md5($feed_url) . '.json';
    
    // Buat direktori cache jika belum ada
    if (!is_dir('cache')) {
        mkdir('cache', 0755, true);
    }
    
    // Periksa cache
    if (file_exists($cache_filename) && (time() - filemtime($cache_filename) < $cache_time)) {
        return json_decode(file_get_contents($cache_filename), true);
    }
    
    // Ambil feed baru
    try {
        $rss = @simplexml_load_file($feed_url);
        
        if (!$rss) {
            throw new Exception("Tidak dapat memuat feed: $feed_url");
        }
        
        $items = [];
        $count = 0;
        
        // Ekstrak dan filter item
        foreach ($rss->channel->item as $item) {
            // Batasi jumlah item
            if ($count >= 10) break;
            
            $title = (string)$item->title;
            $description = (string)$item->description;
            $content = $title . ' ' . $description;
            
            // Filter berdasarkan kata kunci jika diperlukan
            if (!empty($keywords)) {
                $match_found = false;
                foreach ($keywords as $keyword) {
                    if (stripos($content, $keyword) !== false) {
                        $match_found = true;
                        break;
                    }
                }
                
                if (!$match_found) {
                    continue; // Lewati item yang tidak sesuai kata kunci
                }
            }
            
            // Ekstrak gambar dari content jika ada
            $image_url = '';
            if (isset($item->enclosure) && isset($item->enclosure['url'])) {
                $image_url = (string)$item->enclosure['url'];
            } else {
                // Coba ekstrak gambar dari description HTML
                preg_match('/<img[^>]+src="([^">]+)"/', $description, $matches);
                if (isset($matches[1])) {
                    $image_url = $matches[1];
                }
            }
            
            $items[] = [
                'title' => $title,
                'link' => (string)$item->link,
                'description' => strip_tags($description),
                'pubDate' => (string)$item->pubDate,
                'image' => $image_url,
                'source' => parse_url($feed_url, PHP_URL_HOST)
            ];
            
            $count++;
        }
        
        // Simpan ke cache
        file_put_contents($cache_filename, json_encode($items));
        
        return $items;
    } catch (Exception $e) {
        error_log($e->getMessage());
        
        // Jika ada error, coba gunakan cache lama jika tersedia
        if (file_exists($cache_filename)) {
            return json_decode(file_get_contents($cache_filename), true);
        }
        
        return [];
    }
}

// Fungsi untuk menggabungkan feed dari berbagai sumber
function getAllAgricultureFeeds($sources, $keywords = []) {
    $all_items = [];
    
    foreach ($sources as $source_name => $feed_url) {
        $items = getAgricultureFeed($feed_url, 3600, $keywords);
        
        foreach ($items as &$item) {
            $item['source_name'] = $source_name;
        }
        
        $all_items = array_merge($all_items, $items);
    }
    
    // Urutkan berdasarkan tanggal terbaru
    usort($all_items, function($a, $b) {
        return strtotime($b['pubDate']) - strtotime($a['pubDate']);
    });
    
    return $all_items;
}

// Ambil semua berita pertanian
$agriculture_news = getAllAgricultureFeeds($feed_url, $agriculture_keywords);

// Simpan hasil ke cache global
$cache_file = 'cache/all_agriculture_news.json';
file_put_contents($cache_file, json_encode($agriculture_news));

?>