<?php

$config = array();

if (getenv('HTTPS') === 'true') {
    $config['proto'] = 'https';

} else {
    $config['proto'] = 'http';
}
// get land in lowercase from cookie
$config['rooturl'] = $config['proto'] .'://'. getenv('NAME_SERVER') .'/';
$config['multi_language'] = getenv('MULTI_LANGUAGE') === 'true';
if ($config['multi_language']) {
    $config['lang'] = strtolower($lang);
    $config['langurl'] = $config['rooturl'] . $config['lang'] . '/';
} else {
    $config['lang'] = '';
    $config['langurl'] = $config['rooturl'];
}
$config['title'] = 'template';
$config['long_title'] = 'template.com';

/* picture displayed on the main page */
$config['profile_picture'] = $config['rooturl'].'assets/img/kappa.png';
$config['profile_picture_border'] = true;
$config['profile_picture_border_color'] = '';
/* SEO description for meta tags maximum 160 characters */
$config['meta_description'] = 'short description template';
$config['keywords'] = 'keywords, cybersecurity, template';
$config['longer_description'] = 'longer description template';
$config['copyright_name'] = 'Ooggle';
$config['image_description'] = $config['rooturl'].'assets/img/kappa.png';

/* center images in posts */
$config['center_images'] = false;

/* navbar menu (you cannot create submenu of submenu) */
$config['navbar'] = array(
    '/new_tag' => 'tag/new_tag',
    '/posts' => [
        '/samples' => 'tag/sample'
    ]
);

/* social link list */
$config['socials'] = array(
	array(
        'image' => $config['rooturl'].'assets/img/icons/twitter.png',
        'url'   => 'https://twitter.com/'
    ),
    array(
        'image' => $config['rooturl'].'assets/img/icons/github.png',
        'url'   => 'https://github.com/'
    ),
    array(
        'image' => $config['rooturl'].'assets/img/icons/rootme.png',
        'url'   => 'https://www.root-me.org/'
    )
);

/* friend list */
$config['friends'] = array(
	array(
        'image' => $config['rooturl'].'assets/img/kappa.png',
        'url'   => 'https://en.wikipedia.org/wiki/Kappa_(folklore)'
    ),
    array(
        'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/MET_10_211_1857_O1_sf.jpg/1920px-MET_10_211_1857_O1_sf.jpg',
        'url'   => 'https://en.wikipedia.org/wiki/Kappa_(folklore)'
    )
);


/* to configure the database, run http://website.com/admin/setup */
$config['enable_stats'] = false;

/* db */
// get creds from environment variables
$config['db_host'] = 'blogdb'; 
$config['db_name'] = getenv('MYSQL_DATABASE');
$config['db_user'] = getenv('MYSQL_USER');
$config['db_password'] = getenv('MYSQL_PASSWORD');

/* colors */  
$config['first_accent_color'] = '#212121';
$config['sub_accent_color'] = '#f9a825';
$config['footer_accent_color'] = 'black';

