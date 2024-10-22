<?php
// Load the configuration file
include('../website.conf.php');
$jsonFile = '../sitemap.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

$baseUrl = rtrim($config['rooturl'], '/') . '/';
$multiLanguage = $config['multi_language'];

// Function to escape XML entities
function escapeXml($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

// Create a new DOMDocument
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

// Create the <urlset> element
$urlset = $doc->createElement('urlset');
$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

// Collect the tags
$tags = [];
foreach ($data['posts'] as $post) {
    if (!isset($post['hidden']) || !$post['hidden']) {
        foreach ($post['tags'] as $tag) {
            if (!in_array($tag, $tags)) {
                $tags[] = $tag;
            }
        }
    }
}

// Add the URLs for the tags
foreach ($tags as $tag) {
    $urlTag = $doc->createElement('url');
    $locTag = $doc->createElement('loc', $baseUrl . ($multiLanguage ? $config['lang'] . '/' : '') . 'tag/' . escapeXml($tag));
    $changefreqTag = $doc->createElement('changefreq', 'weekly');
    $priorityTag = $doc->createElement('priority', '0.5');
    $urlTag->appendChild($locTag);
    $urlTag->appendChild($changefreqTag);
    $urlTag->appendChild($priorityTag);
    $urlset->appendChild($urlTag);
}

// Add the URLs for the posts
foreach ($data['posts'] as $post) {
    if (!isset($post['hidden']) || !$post['hidden']) {
        $urlPost = $doc->createElement('url');
        $locPost = $doc->createElement('loc', $baseUrl . ($multiLanguage ? $config['lang'] . '/' : '') . 'post/' . escapeXml($post['url']));
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

// Add the URLs for the homepage, whoami and tag pages
$urlWhoami = $doc->createElement('url');
$locWhoami = $doc->createElement('loc', $baseUrl . ($multiLanguage ? $config['lang'] . '/' : '') . 'whoami');
$changefreqWhoami = $doc->createElement('changefreq', 'monthly');
$priorityWhoami = $doc->createElement('priority', '0.8');
$urlWhoami->appendChild($locWhoami);
$urlWhoami->appendChild($changefreqWhoami);
$urlWhoami->appendChild($priorityWhoami);
$urlset->appendChild($urlWhoami);

$urlPost = $doc->createElement('url');
$locPost = $doc->createElement('loc', $baseUrl . ($multiLanguage ? $config['lang'] . '/' : '') . 'post');
$changefreqPost = $doc->createElement('changefreq', 'daily');
$priorityPost = $doc->createElement('priority', '0.7');
$urlPost->appendChild($locPost);
$urlPost->appendChild($changefreqPost);
$urlPost->appendChild($priorityPost);
$urlset->appendChild($urlPost);

$urlTag = $doc->createElement('url');
$locTag = $doc->createElement('loc', $baseUrl . ($multiLanguage ? $config['lang'] . '/' : '') . 'tag');
$changefreqTag = $doc->createElement('changefreq', 'daily');
$priorityTag = $doc->createElement('priority', '0.6');
$urlTag->appendChild($locTag);
$urlTag->appendChild($changefreqTag);
$urlTag->appendChild($priorityTag);
$urlset->appendChild($urlTag);

// Add the URL for the homepage
$urlHome = $doc->createElement('url');
$locHome = $doc->createElement('loc', $baseUrl . ($multiLanguage ? $config['lang'] . '/' : ''));
$changefreqHome = $doc->createElement('changefreq', 'daily');
$priorityHome = $doc->createElement('priority', '1.0');
$urlHome->appendChild($locHome);
$urlHome->appendChild($changefreqHome);
$urlHome->appendChild($priorityHome);
$urlset->appendChild($urlHome);

$doc->appendChild($urlset);

// Save the XML file
$doc->save('../sitemap.xml');

echo "The sitemap.xml file has been successfully generated.";
?>
