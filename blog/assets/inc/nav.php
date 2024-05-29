<nav id="top-navbar" style="background-color: <?php echo $config['first_accent_color'] ?>" role="navigation" style="position: sticky; top: 0;">
    <div class="nav-wrapper container">
        <a id="logo-container" href="<?php echo $config['rooturl'] ?>" class="brand-logo">/<?php echo $config['long_title'] ?></a>
        <ul id="nav-actions-ul" class="right hide-on-med-and-down">
            <li id="search-li-wide" style="height: 64px;">
                <form action="<?php echo $config['rooturl'] ?>search">
                    <div class="input-field">
                    <input id="search-wide" name="q" type="search" required>
                    <label class="label-icon" for="search-wide"><i class="material-icons">search</i></label>
                    <i class="material-icons">close</i>
                    </div>
                </form>
            </li>
            <?php
                if(isset($config['navbar']))
                {
                    $submenu_count = 0;
                    foreach($config['navbar'] as $key => $menu)
                    {
                        if(gettype($menu) === 'string')
                        {
                            echo '<li><a href="' . return_url($menu) . '">' . $key . '</a></li>';
                        }
                        else if(gettype($menu) === 'array')
                        {
                            echo '<li><a href="#" class="dropdown-trigger" data-target="dropdown' . $submenu_count . '-menu"><i class="material-icons right">arrow_drop_down</i>' . $key . '</a></li>';
                            echo '<ul id="dropdown' . $submenu_count . '-menu" class="dropdown-content" style="background-color: ' . $config['first_accent_color'] . '">';
                            foreach($menu as $subkey => $submenu)
                            {
                                if(gettype($submenu) === 'string')
                                {
                                    echo '<li><a href="' . return_url($submenu) . '">' . $subkey . '</a></li>';
                                }
                            }
                            echo '</ul>';
                            $submenu_count += 1;
                        }
                    }
                }
            ?>

                <?php
                // Définir les icônes de drapeau en fonction de la langue actuelle
                $lang_to_flag = [
                    'EN' => '🇺🇸',  // Anglais (États-Unis)
                    'FR' => '🇫🇷',  // Français (France)
                    'JA' => '🇯🇵',  // Japonais (Japon)
                    'DE' => '🇩🇪',  // Allemand (Allemagne)
                    'ES' => '🇪🇸',  // Espagnol (Espagne)
                    'IT' => '🇮🇹',  // Italien (Italie)
                    'PT' => '🇵🇹',  // Portugais (Portugal)
                    'RU' => '🇷🇺',  // Russe (Russie)
                    'ZH' => '🇨🇳',  // Chinois (Chine)
                    'KO' => '🇰🇷',  // Coréen (Corée du Sud)
                    'NL' => '🇳🇱',  // Néerlandais (Pays-Bas)
                    'SV' => '🇸🇪',  // Suédois (Suède)
                    'DA' => '🇩🇰',  // Danois (Danemark)
                    'FI' => '🇫🇮',  // Finnois (Finlande)
                    'NO' => '🇳🇴',  // Norvégien (Norvège)
                    'PL' => '🇵🇱',  // Polonais (Pologne)
                    'TR' => '🇹🇷',  // Turc (Turquie)
                    'AR' => '🇸🇦',  // Arabe (Arabie Saoudite)
                    'HE' => '🇮🇱',  // Hébreu (Israël)
                    'HI' => '🇮🇳',  // Hindi (Inde)
                    'TH' => '🇹🇭',  // Thaï (Thaïlande)
                    'VI' => '🇻🇳',  // Vietnamien (Vietnam)
                    'EL' => '🇬🇷',  // Grec (Grèce)
                    'HU' => '🇭🇺',  // Hongrois (Hongrie)
                    'CS' => '🇨🇿',  // Tchèque (République Tchèque)
                    'SK' => '🇸🇰',  // Slovaque (Slovaquie)
                    'RO' => '🇷🇴',  // Roumain (Roumanie)
                    'BG' => '🇧🇬',  // Bulgare (Bulgarie)
                    'UK' => '🇺🇦',  // Ukrainien (Ukraine)
                    'HR' => '🇭🇷',  // Croate (Croatie)
                    'SR' => '🇷🇸',  // Serbe (Serbie)
                    'LT' => '🇱🇹',  // Lituanien (Lituanie)
                    'LV' => '🇱🇻',  // Letton (Lettonie)
                    'EE' => '🇪🇪',  // Estonien (Estonie)
                ];

                // Vérifier si le cookie 'lang' est défini et contient une langue valide
                $current_lang = isset($_COOKIE['lang']) && array_key_exists($_COOKIE['lang'], $lang_to_flag) ? $_COOKIE['lang'] : false;

                // Si le cookie 'lang' n'est pas défini ou n'est pas valide, le définir à 'false'
                if (!$current_lang) {
                    setcookie('lang', 'notSupported', time() + 365*24*60*60, '/');
                    $flag_icon = false;
                } else {
                    $flag_icon = $lang_to_flag[$current_lang];
                }
                ?>

            <li><a href="<?php echo $config['rooturl'] ?>whoami" class="tooltipped" data-position="bottom" data-tooltip="( ͡° ͜ʖ ͡°)">/whoami</a></li>
            <li><a onclick="switch_dark_white()" class="btn-floating waves-effect waves-light transparent"><i id="theme-switch-button" class="material-icons"><?php echo (isset($_COOKIE['dark-mode']) && $_COOKIE['dark-mode'] == 0) ? 'brightness_3' : 'brightness_7' ?></i></a></li>
            <?php if ($flag_icon && $_COOKIE['multilang'] === 'true'): ?>
                <li><a onclick="switch_lang()" class="btn-floating waves-effect waves-light transparent"><i id="theme-switch-button" class="material-icons"><?php echo $flag_icon; ?></i></a></li>
            <?php endif; ?>
        </ul>

        <ul id="nav-mobile" class="sidenav grey darken-4">
            <li style="height: 64px;" class="grey darken-3">
                <form action="<?php echo $config['rooturl'] ?>search">
                    <div class="input-field">
                    <input id="search" name="q" type="search" required>
                    <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                    <i class="material-icons">close</i>
                    </div>
                </form>
            </li>
            <?php
                if(isset($config['navbar']))
                {
                    foreach($config['navbar'] as $key => $menu)
                    {
                        if(gettype($menu) === 'string')
                        {
                            echo '<li><a class="grey-text text-lighten-5" href="' . return_url($menu) . '">' . $key . '</a></li>';
                        }
                        else if(gettype($menu) === 'array')
                        {
                            echo '<li class="divider" tabindex="-1"></li>';
                            echo '<li><a class="grey-text text-lighten-5" href="#">' . $key . '</a></li>';
                            foreach($menu as $subkey => $submenu)
                            {
                                if(gettype($submenu) === 'string')
                                {
                                    echo '<li><a class="grey-text text-lighten-5" href="' . return_url($submenu) . '">𑁋 ' . $subkey . '</a></li>';
                                }
                            }
                            echo '<li class="divider" tabindex="-1"></li>';
                        }
                    }
                }
            ?>
            <li><a class="grey-text text-lighten-5" href="<?php echo $config['rooturl'] ?>whoami">/whoami</a></li>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons grey-text text-lighten-">menu</i></a>
    </div>
</nav>
<div id="go-to-top-button" class="fixed-action-btn">
    <a class="btn-floating btn-large red">
        <i class="large material-icons">keyboard_arrow_up</i>
    </a>
</div>