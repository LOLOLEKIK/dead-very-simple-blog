<script src="<?php echo $config['rooturl'] ?>assets/js/materialize.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js?skin=desert"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* mobile navbar */
        var elems = document.querySelectorAll('.sidenav');
        var instances = M.Sidenav.init(elems);

        /* dropdown */
        var elems = document.querySelectorAll('.dropdown-trigger');
        var instances = M.Dropdown.init(elems, {"constrainWidth": false});

        /* tooltips */
        var elems = document.querySelectorAll('.tooltipped');
        var instances = M.Tooltip.init(elems);

        /* Floating Action Button */
        var elems = document.querySelectorAll('.fixed-action-btn');
        var instances = M.FloatingActionButton.init(elems);

        /* check for navbar to show up or not */
        if (window.pageYOffset < 300) {
            document.getElementById("top-navbar").style.top = "0";
        } else {
            document.getElementById("top-navbar").style.top = "-65px";
        }

        /* check for go-to-top button to show up or not */
        if(window.pageYOffset < 300) {
            document.getElementById("go-to-top-button").style.bottom = "-100px";
        } else {
            document.getElementById("go-to-top-button").style.bottom = "23px";
        }
    });

    /* go to top button */
    document.getElementById("go-to-top-button").addEventListener('click', function() {
        topFunction();
    });

    /* When the user scrolls down, hide the navbar. When the user scrolls up, show the navbar */
    var prevScrollpos = window.pageYOffset;
    window.onscroll = function() {
        var currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            document.getElementById("top-navbar").style.top = "0";
        } else if(currentScrollPos > 300) {
            document.getElementById("top-navbar").style.top = "-65px";
        }
        
        /* check for go-to-top button to show up or not */
        if(currentScrollPos < 300) {
            document.getElementById("go-to-top-button").style.bottom = "-100px";
        } else {
            document.getElementById("go-to-top-button").style.bottom = "23px";
        }
        prevScrollpos = currentScrollPos;
    }

    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
	
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function setCookie(cname, cvalue) {
        var d = new Date();
        d.setTime(d.getTime() + (365*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function switch_dark_white()
    {
        if(dark == 0)
        {
            setCookie("dark-mode", 1);
            dark = 1;

            var item = document.getElementById("theme-style"); 
            item.parentNode.removeChild(item);
            var item = document.getElementById("markdown-style"); 
            item.parentNode.removeChild(item);

            var node = document.createElement("link");
            node.rel = "stylesheet";
            node.href = "<?php echo $config['rooturl'] ?>assets/css/style-dark-specific.css";
            node.id = "theme-style"
            document.head.appendChild(node);
            var node = document.createElement("link");
            node.rel = "stylesheet";
            node.href = "<?php echo $config['rooturl'] ?>assets/css/github-markdown-dark.css";
            node.id = "markdown-style"
            document.head.appendChild(node);

            document.getElementById("theme-switch-button").innerHTML = "brightness_7";
        }
        else
        {
            setCookie("dark-mode", 0);
            dark = 0;

            var item = document.getElementById("theme-style"); 
            item.parentNode.removeChild(item);
            var item = document.getElementById("markdown-style"); 
            item.parentNode.removeChild(item);

            var node = document.createElement("link");
            node.rel = "stylesheet";
            node.href = "<?php echo $config['rooturl'] ?>assets/css/style-white-specific.css";
            node.id = "theme-style"
            document.head.appendChild(node);
            var node = document.createElement("link");
            node.rel = "stylesheet";
            node.href = "<?php echo $config['rooturl'] ?>assets/css/github-markdown.css";
            node.id = "markdown-style"
            document.head.appendChild(node);

            document.getElementById("theme-switch-button").innerHTML = "brightness_3";
        }
        
    }


    var dark = getCookie("dark-mode");
    if(dark == "0")
    {
        dark = 0;
    }
    else if (dark == "" || dark == "1") {
        dark = 1;
    }
    else
    {
        dark = 1;
    }

    function clearCookies() {
            // Clear all cookies
            document.cookie.split(";").forEach(function(cookie) {
                const cookieName = cookie.split("=")[0];
                document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            });
    }

  

    /* footer */

    if(document.body.scrollHeight < window.innerHeight) {
        document.body.style.minHeight = "100vh";
        document.getElementById("page-footer").style.position = "absolute";
        document.getElementById("page-footer").style.bottom = "0px";
    }

// manage languages

    function switch_lang() {
        fetch('/lang.php')
        .then(response => response.json())
        .then(languages => {

            if (languages.error) {
                console.error(languages.error);
                return;
            }
            languages = sanitize_list_of_languages(languages);


            // Obtenir la langue actuelle à partir du cookie
            let currentLang = getCookie('lang');

            // Trouver l'index de la langue actuelle dans le tableau des langues
            let currentIndex = languages.indexOf(currentLang);

            // Calculer l'index de la langue suivante
            let nextIndex = (currentIndex + 1) % languages.length;

            // Définir le cookie sur la langue suivante
            setCookie('lang', languages[nextIndex], 365);
            if (languages.length == 0){
                setCookie('lang', false, 365);
                setCookie('multilang', false, 365);


            } else if (languages.length == 1){
                setCookie('lang', languages[0], 365);
                setCookie('multilang', false, 365);
            }
            else {
                setCookie('multilang', true, 365);
            }

            // Optionnel : recharger la nouvelle langue en mettant au début de l'url la langue (en lowercase) et en gardant le chemin (mais en supprimant la langue actuelle)
            // Par exemple, si l'URL actuelle est /en/blog, et que la langue suivante est "fr", la nouvelle URL sera /fr/blog
            // Si l'URL actuelle est /blog, et que la langue suivante est "fr", la nouvelle URL sera /fr/blog
            // Si l'URL actuelle est /blog, et que la langue suivante est "en", la nouvelle URL sera /en/blog
            // Si l'URL actuelle est /en/blog, et que la langue suivante est "en", la nouvelle URL sera /en/blog
            // Si l'URL actuelle est /fr/search?q=test, et que la langue suivante est "en", la nouvelle URL sera /en/search?q=test
            // Si l'URL actuelle est /en/search?q=test, et que la langue suivante est "fr", la nouvelle URL sera /fr/search?q=test
            lowercaseLang = languages[nextIndex].toLowerCase();
            window.location.replace('/' + lowercaseLang + window.location.pathname.replace(/^\/[a-z]{2}\//, '/') + window.location.search);



            
            

            
        })
        .catch(error => console.error('Error fetching the languages:', error));
    }

function sanitize_list_of_languages(languages) {

            // array with all country codes
            possible_country = ['FR', 'EN', 'JA', 'DE', 'ES', 'IT', 'PT', 'RU', 'ZH', 'KO', 'NL', 'SV', 'DA', 'FI', 'NO', 'PL', 'TR', 'AR', 'HE', 'HI', 'TH', 'VI', 'EL', 'HU', 'CS', 'SK', 'RO', 'BG', 'UK', 'HR', 'SR', 'LT', 'LV', 'EE'];

        // Remove unsupported languages
        for (var i = 0; i < languages.length; i++) {
            if (!possible_country.includes(languages[i])) {
                languages.splice(i, 1);
                i--;
            }
        }
        return languages;
}

    function get_first_lang() {
        fetch('/lang.php')
        .then(response => response.json())
        .then(languages => {

            if (languages.error) {
                console.error(languages.error);
                return;
            }
            languages = sanitize_list_of_languages(languages);
            var lang = getCookie("lang");


            // Définir le cookie sur la langue suivante
            if (languages.length > 1){
                setCookie('lang', languages[0], 365);
                setCookie('multilang', true, 365);
                location.reload();
            } else if (languages.length == 1){
                setCookie('lang', languages[0], 365);
                setCookie('multilang', false, 365);
                if(lang == "notSupported")
                {
                    location.reload();
                }

            } else {
                setCookie('lang', false, 365);
                setCookie('multilang', true, 365);
            }

        })
        .catch(error => console.error('Error fetching the languages:', error));
    }

    var lang = getCookie("lang");
    var multilang = getCookie("multilang");
    if (lang == "" || lang == "false" || lang == null || multilang == "false" || multilang == "" || multilang == null) {
        get_first_lang();
       
    }

    // Add this at the end of the file
    function copyCode(button) {
        const pre = button.parentElement;
        const code = pre.querySelector('code');
        const range = document.createRange();
        range.selectNode(code);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        
        try {
            document.execCommand('copy');
            button.textContent = 'Copied!';
            setTimeout(() => {
                button.textContent = 'Copy';
            }, 2000);
        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
        
        window.getSelection().removeAllRanges();
    }
</script>