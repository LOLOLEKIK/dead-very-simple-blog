<?php
header('Content-Type: application/json');
$filename = 'sitemap.json';

if (file_exists($filename)) {
    $json = file_get_contents($filename);
    $data = json_decode($json, true);

    if (isset($data['posts']) && is_array($data['posts'])) {
        $languages = [];

        foreach ($data['posts'] as $post) {
            if ($post['hidden'] !== true) {
                if (isset($post['file']) && is_array($post['file'])) {
                    foreach ($post['file'] as $lang => $file) {
                        if (!in_array($lang, $languages)) {
                            $languages[] = $lang;
                        }
                    }
                }
            }
        }

        sort($languages); // Trier par ordre alphabétique
        echo json_encode($languages);
    } else {
        echo json_encode(['error' => 'Invalid posts format']);
    }
} else {
    echo json_encode(['error' => 'File not found']);
}
?>
