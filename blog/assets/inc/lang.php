<?php
                // Define the language to flag mapping
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

                $path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                $langUrl = $path[0];
                // convert lang in uppercase
                $lang = strtoupper($langUrl);

                if (isset($_COOKIE['lang']) && $lang === '') {
                    $lang = $_COOKIE['lang'];
                }


                if (!array_key_exists($lang, $lang_to_flag)) {
                    // Define default lang here
                    $lang = 'EN';
                }

                if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] != $lang) {
                    setcookie('lang', $lang, time() + (3600 * 24 * 30), '/'); // expire in 30 days
                }                
     

                // check if the language is supported
                $current_lang = isset($_COOKIE['lang']) && array_key_exists($lang, $lang_to_flag) ? $lang : false;

                // if the language is not supported, set the cookie to "notSupported" and don't display the flag icon
                if (!$current_lang) {
                    setcookie('lang', "notSupported", null, time() + 365*24*60*60, '/');
                    $flag_icon = false;
                } else {
                    $flag_icon = $lang_to_flag[$current_lang];
                }
?>