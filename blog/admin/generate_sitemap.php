<?php
// Charger le contenu du fichier JSON
include('../website.conf.php');
$jsonFile = '../sitemap.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

$baseUrl = rtrim($config['rooturl'], '/') . '/';

// Fonction pour échapper les caractères XML spéciaux
function escapeXml($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

// Créer une nouvelle instance de DOMDocument
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

// Créer l'élément de base <urlset>
$urlset = $doc->createElement('urlset');
$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

// Collecte des tags par langue
$tagsByLang = [];

// Collecter les tags en fonction des langues disponibles dans les fichiers
foreach ($data['posts'] as $post) {
    if (!$post['hidden']) {
        foreach ($post['tags'] as $tag) {
            foreach ($post['file'] as $lang => $file) {
                $lang = strtolower($lang);
                if (!isset($tagsByLang[$lang])) {
                    $tagsByLang[$lang] = [];
                }
                if (!in_array($tag, $tagsByLang[$lang])) {
                    $tagsByLang[$lang][] = $tag;
                }
            }
        }
    }
}

// Ajouter les URL des tags par langue
foreach ($tagsByLang as $lang => $tags) {
    foreach ($tags as $tag) {
        $urlTag = $doc->createElement('url');
        $locTag = $doc->createElement('loc', $baseUrl . $lang . '/tag/' . escapeXml($tag));
        $changefreqTag = $doc->createElement('changefreq', 'weekly');
        $priorityTag = $doc->createElement('priority', '0.5');
        $urlTag->appendChild($locTag);
        $urlTag->appendChild($changefreqTag);
        $urlTag->appendChild($priorityTag);
        $urlset->appendChild($urlTag);
    }
}

// Ajouter les URL des posts
foreach ($data['posts'] as $post) {
    if (!$post['hidden']) {
        foreach ($post['file'] as $lang => $file) {
            $lang = strtolower($lang);
            $urlPost = $doc->createElement('url');
            $locPost = $doc->createElement('loc', $baseUrl . $lang . '/post/' . escapeXml($post['url']));
            $lastmodPost = $doc->createElement('lastmod', $post['date']);
            $changefreqPost = $doc->createElement('changefreq', 'monthly');
            $priorityPost = $doc->createElement('priority', '1');
            $urlPost->appendChild($locPost);
            $urlPost->appendChild($lastmodPost);
            $urlPost->appendChild($changefreqPost);
            $urlPost->appendChild($priorityPost);
            $urlset->appendChild($urlPost);
        }
    }
}

// Ajouter les URLs spécifiques pour chaque langue
foreach ($tagsByLang as $lang => $tags) {
    // URL /whoami
    $urlWhoami = $doc->createElement('url');
    $locWhoami = $doc->createElement('loc', $baseUrl . $lang . '/whoami');
    $changefreqWhoami = $doc->createElement('changefreq', 'monthly');
    $priorityWhoami = $doc->createElement('priority', '0.8');
    $urlWhoami->appendChild($locWhoami);
    $urlWhoami->appendChild($changefreqWhoami);
    $urlWhoami->appendChild($priorityWhoami);
    $urlset->appendChild($urlWhoami);

    // URL /post
    $urlLangPost = $doc->createElement('url');
    $locLangPost = $doc->createElement('loc', $baseUrl . $lang . '/post');
    $changefreqLangPost = $doc->createElement('changefreq', 'daily');
    $priorityLangPost = $doc->createElement('priority', '0.7');
    $urlLangPost->appendChild($locLangPost);
    $urlLangPost->appendChild($changefreqLangPost);
    $urlLangPost->appendChild($priorityLangPost);
    $urlset->appendChild($urlLangPost);

    // URL /tag
    $urlLangTag = $doc->createElement('url');
    $locLangTag = $doc->createElement('loc', $baseUrl . $lang . '/tag');
    $changefreqLangTag = $doc->createElement('changefreq', 'daily');
    $priorityLangTag = $doc->createElement('priority', '0.6');
    $urlLangTag->appendChild($locLangTag);
    $urlLangTag->appendChild($changefreqLangTag);
    $urlLangTag->appendChild($priorityLangTag);
    $urlset->appendChild($urlLangTag);
}

// Ajouter <urlset> au document
$doc->appendChild($urlset);

// Enregistrer le fichier XML
$doc->save('../sitemap.xml');

echo "The sitemap.xml file has been successfully generated.";
?>
