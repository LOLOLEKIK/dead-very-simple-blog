<?php

include('assets/inc/lang.php');
include('website.conf.php');
include('assets/inc/utils.php');

if(isset($_GET['file']) && $_GET['file'] != '') {
    // parse sitemap
    $previousPost = '-1';
    $selectedPost = '-1';
    $followingPost = '-1';
    $sitemap = json_decode(fread(fopen("sitemap.json", "r"), filesize("sitemap.json")));

    $current_lang = $lang;

    foreach ($sitemap->posts as $key => $post) {
        if(isset($post->hidden) && $post->hidden == true && $_GET["file"] !== $post->url) {
            unset($sitemap->posts[$key]);
        }
    }

    foreach ($sitemap->posts as $key => $post) {
        if($_GET['file'] === $post->url) {
            if(isset($sitemap->posts[$key - 1])) {
                $previousPost = $sitemap->posts[$key - 1];
            }
            $selectedPost = $post;
            if(isset($sitemap->posts[$key + 1])) {
                $followingPost = $sitemap->posts[$key + 1];
            }
            break;
        }
    }

    if($selectedPost != '-1') {
        // nice
    } else {
        return_404();
    }
} else {
    return_404();
}

$og_image = NULL;

/* CHEMIN DU FICHIER EN FONCTION DE LA LANGUE */
if(is_object($selectedPost->file) && isset($selectedPost->file->$current_lang)) {
    $file_path = $selectedPost->file->$current_lang;
} elseif(is_string($selectedPost->file)) {
    $file_path = $selectedPost->file;
} else {
    return_404();
}

/* CHECK THE FILE EXTENSION */
if((substr($file_path, strlen($file_path) - 3) === 'php') || (substr($file_path, strlen($file_path) - 4) === 'html')) {
    $file = fread(fopen($file_path, "r"), filesize($file_path));

    $re = '/<img src="(.+?)(?=\")/m';
    preg_match($re, $file, $matches, PREG_OFFSET_CAPTURE, 0);
    if(isset($matches[1][0]) && $matches[1][0] !== '') {
        $og_image = return_url($matches[1][0]);
    }
} else if((substr($file_path, strlen($file_path) - 2) === 'md')) {
    include("assets/inc/Parsedown.php");

    $Parsedown = new Parsedown();

    $mdfile = fread(fopen($file_path, "r"), filesize($file_path));

    // Process image paths
    $mdfile = preg_replace_callback('/!\[([^\]]*)\]\(([^)]+)\)/', function($matches) use ($selectedPost, $config) {
        $alt = $matches[1];
        $src = $matches[2];
        if (!preg_match("~^(?:f|ht)tps?://~i", $src)) {
            // Construct the full path to the image
            $src = $config['rooturl'] . $selectedPost->dir . $src;
        }
        return "![$alt]($src)";
    }, $mdfile);

    $mdfile = $Parsedown->text($mdfile);

    // Add prettyprint class to code blocks while preserving existing classes
    $mdfile = preg_replace('/<code class="(.*?)"/', '<code class="$1 prettyprint"', $mdfile);

    // Add copy button to code blocks
    $mdfile = preg_replace('/<code(.*?)>(.*?)<\/code>/s', '<code$1><button class="copy-btn" onclick="copyCode(this)">Copy</button>$2</code>', $mdfile);

    /* og image search */
    $re = '/<img src="(.+?)(?=\")/m';
    preg_match($re, $mdfile, $matches, PREG_OFFSET_CAPTURE, 0);
    if(isset($matches[1][0]) && $matches[1][0] !== '') {
        $og_image = $matches[1][0];
    }
}

/* OG image choice */
if($og_image === NULL) {
    $og_image = return_url($config['profile_picture']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('assets/inc/global_head.php'); ?>
    <title><?php echo $selectedPost->title ?> | <?php echo $config['title'] ?></title>
    <meta property="og:title" content="<?php echo $selectedPost->title ?>. Tags:<?php
    $i = 0;
    foreach(get_tag_list($selectedPost->url) as $key => $tag) {
        if($i == 0) {
            $i += 1;
            echo "{$tag}";
        }
        echo " - {$tag}";
    }
    ?>">
    <meta property="og:image" content="<?php echo $og_image ?>">
    <meta name="description" content="<?php echo $selectedPost->title ?>">
    <meta name="keywords" content="<?php
    $i = 0;
    foreach(get_tag_list($selectedPost->url) as $key => $tag) {
        if($i == 0) {
            $i += 1;
            echo "{$tag}";
        }
        echo ", {$tag}";
    }
    ?>">
</head>
<body>
    <?php include('assets/inc/nav.php') ?>
    <nav style="background-color: <?php echo $config['sub_accent_color'] ?>; overflow: hidden">
        <div class="container">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="<?php echo $config['langurl'] ?>" class="breadcrumb"><?php echo $config['long_title'] ?></a>
                    <a href="<?php echo $config['langurl'] ?>posts/" class="breadcrumb">post</a>
                    <a href="" class="breadcrumb"><?php echo $selectedPost->title ?></a>
                </div>
            </div>
        </div>
    </nav>
    <br>
    <?php

    echo '<div class="container">';
    echo '<p class="theme-font-color">title: ' . $selectedPost->title . '<br>';
    echo 'date: ' . date("M d, Y", strtotime($selectedPost->date)) . '<br>';
    echo 'tags: ';
    foreach (get_tag_list($selectedPost->url) as $key => $tag) {
        echo '<a href="' . $config['langurl'] . 'tag/' . $tag . '">' . $tag . '</a> ';
    }
    echo '</p>';
    echo '</div>';

    ?>
    <br>
    <?php
    echo '<div class="markdown-body container">';

    /* CHECK THE FILE EXTENSION */
    if((substr($file_path, strlen($file_path) - 3) === 'php') || (substr($file_path, strlen($file_path) - 4) === 'html')) {
        include($file_path);
    } else if((substr($file_path, strlen($file_path) - 2) === 'md')) {
        echo $mdfile;
    }

    echo '</div>';

    ?>
    <br>
    <div class="grey darken-4">
        <div class="row container" style="margin-bottom: 0;">
            <div class="col s12 m6">
                <?php
                    echo $previousPost !== '-1' ? '<a href="' . $config['langurl'] . 'post/' . $previousPost->url . '" class="breadcrumb"><p><i class="material-icons left">keyboard_arrow_left</i> ' . $previousPost->title . '</p></a>' : '';
                ?>
            </div>
            <div class="col s12 m6 right-align">
                <?php
                    echo $followingPost !== '-1' ? '<a href="' . $config['langurl'] . 'post/' . $followingPost->url . '" class="breadcrumb"><p>' . $followingPost->title . ' <i class="material-icons right">keyboard_arrow_right</i></p></a>' : '';
                ?>
            </div>
        </div>
    </div>
    <?php include('assets/inc/footer.php'); ?>
    <?php include('assets/inc/global_js.php'); ?>
</body>
</html>
