![header](images/header.png)

# dead-simple-blog

[![img release](https://img.shields.io/github/commit-activity/m/Ooggle/dead-simple-blog.svg?sanitize=true&color=blue)](#)
[![img last commit](https://img.shields.io/github/last-commit/Ooggle/dead-simple-blog.svg)](#)
[![img last release](https://img.shields.io/github/release/Ooggle/dead-simple-blog.svg?color=red)](#)
[![img last release](https://img.shields.io/twitter/follow/Ooggule.svg?style=social)](https://twitter.com/Ooggule)

dead-simple blog template powered by Markdown and PHP

# Why this fork

I needed to redo my blog, but the proposed architecture and certificate management made using this superb framework more complex in my architecture.

I preferred to recreate a "very" simple version of this framework.

The final objective of this fork is to provide ONLY the techno stack that runs the blog.

The changes :
- Reverse proxy removed
- Unification of deployment configuration in docker-compose.yml
- Simplified configuration via environment variables
- Removal of unnecessary volumes 

TODO:
- Start directly from an Apache image
- Check that I haven't left any configurations that are no longer needed 

After deployment, you're free to put it in standalone, behind a reverse proxy, with a certbot or in an existing kube or swarm stack :)

I've also modified the sitemap.json strcuture to handle multiple languages.

Here are the supported languages 

```
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
```

If you set the same language, it will not be possible to change it.

## Installation

After git clone, edit `docker-compose.yml` to change the environment variables and that's it!

```sh
cd dead-simple-blog
docker-compose up -d
# Here we go!
```

<br>

## Usage

Everything lies in `website.conf.php` as well as `sitemap.json` files in blog/.

## Stats

To obtain statistics for your site, first activate the feature in `website.conf.php`.

Then go to `/admin/setup` with the user password you set in your docker compose (USERNAME_ADMIN_DASHBOARD/PASSWORD_ADMIN_DASHBOARD).

Then go to `/admin/` or `/admin/dasbhoard` to view your statistics.

#### website.conf.php

In this file, you can setup everything related to the website customization (title, description, header, profile picture, colors...). The variables names are self explanables.

#### sitemap.json

This is where you add your articles. There are example of how to do it in the sample file. Basically, you can add articles which point to etheir `.md` or `.html/.php` file, and the engine will take care of displaying it on the website. Note that you can add `"hidden": true` to an article to hide it from the list (the article can still be accessed via it's URL). The examples shows the different possibilities you have to create perfect articles!

Please be aware that the articles are not sorted by date, but by apparition, meaning the article at the top of the file will be the first to show up.

<br>

## Update guide

#### You can use the update.sh script to update your apache2/src/ directory by doing the following (thanks to [@Kevin-Mizu](https://github.com/Kevin-Mizu)):   
(don't forget to change the path in your command)   
```
wget https://raw.githubusercontent.com/Ooggle/dead-simple-blog/master/update.sh && chmod u+x ./update.sh
./update.sh path/to/your/apache2/src/
```

### Manual update (not recommanded)

In order to update the website to the latest version, you need to download the latest release, copy the content of the new `src/apache2/src/` in your own `apache2/src/` directory.

:warning: If you don't want all your work to be lost, don't copy:   
- assets/inc/whoami.php
- .htaccess   
- articles/   
- favicon.png   
- website.conf.php   
- sitemap.json   

New options may be needed in your `website.conf.php` file. Check the release changelog in order to update your configuration file consequently (if you are updating from the upstream git, check the commit messages).
