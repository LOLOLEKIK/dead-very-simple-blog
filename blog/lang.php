<?php
header('Content-Type: application/json');
$filename = 'sitemap.json';

include('../website.conf.php');

if ($config['multi_language']) {
    $path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $langUrl = $path[0];
    $lang = strtoupper($langUrl);

    if (isset($_COOKIE['lang']) && $lang === '') {
        $lang = $_COOKIE['lang'];
    }

    if (!array_key_exists($lang, $lang_to_flag)) {
        $lang = 'EN';
    }

    if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] != $lang) {
        setcookie('lang', $lang, time() + (3600 * 24 * 30), '/');
    }

    $current_lang = isset($_COOKIE['lang']) && array_key_exists($lang, $lang_to_flag) ? $lang : false;

    if (!$current_lang) {
        setcookie('lang', "notSupported", null, time() + 365*24*60*60, '/');
        $flag_icon = false;
    } else {
        $flag_icon = $lang_to_flag[$current_lang];
    }
} else {
    $lang = 'EN';
    $current_lang = 'EN';
    $flag_icon = false;
}

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
