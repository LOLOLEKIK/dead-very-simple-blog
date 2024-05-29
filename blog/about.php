<?php

include('website.conf.php');
include('assets/inc/utils.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('assets/inc/global_head.php'); ?>
    <title>Whoami | <?php echo $config['title'] ?></title>
    <meta property="og:title" content="Whoami">
    <meta property="og:image" content="<?php echo return_url($config['profile_picture']) ?>">
</head>
<body>
    <?php include('assets/inc/nav.php') ?>
    <nav style="background-color: <?php echo $config['sub_accent_color'] ?>">
        <div class="container">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="<?php echo $config['rooturl'] ?>" class="breadcrumb"><?php echo $config['long_title'] ?></a>
                    <a href="" class="breadcrumb">whoami</a>
                </div>
            </div>
        </div>
    </nav>
    <br><br>
    <div class="container">
        <div class="markdown-body container">
            <?php
                // Capture the output of whoami.php
                ob_start();
                include('assets/inc/whoami.php');
                $output = ob_get_clean();

                // Process the captured output with Parsedown if needed
                include("assets/inc/Parsedown.php");

                $Parsedown = new Parsedown();
                $mdfile = $Parsedown->text($output);

                // Replace references to local markdown directory with full path from website root
                $pattern = array();
                $replacement = array();
                $pattern[0] = '/<code class="/';
                $pattern[1] = '/<code>/';
                $replacement[0] = '<code class="prettyprint ';
                $replacement[1] = '<code class="prettyprint">';
                $mdfile = preg_replace($pattern, $replacement, $mdfile);

                echo $mdfile;
            ?>
        </div>
    </div>
    
    <?php include('assets/inc/footer.php'); ?>
    <?php include('assets/inc/global_js.php'); ?>
</body>
</html>
