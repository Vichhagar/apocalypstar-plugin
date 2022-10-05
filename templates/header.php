<?php
// $icon_001 = file_get_contents('img/icons/icon_001_menu.svg');
// $icon_002 = file_get_contents('img/icons/icon_002_croix.svg');
// $icon_020 = file_get_contents('img/icons/icon_020_maintenance.svg');
// $icon_021 = file_get_contents('img/icons/icon_021_construct.svg');


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->

    <!-- Calendrier -->
    <link rel="stylesheet" href="<?php dirname(__FILE__, 2) . '/assets/css/calendrier.css'?>">

    <!-- Home -->
    <link rel="stylesheet" href="<?php dirname(__FILE__, 2) . '/assets/css/home.css'?>">

    <!-- Menu -->
    <link rel="stylesheet" href="<?php dirname(__FILE__, 2) . '/assets/css/menu.css'?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    



    <script src="<?= plugin_dir_url(dirname(__FILE__, 1)) . 'assets/js/genesis.js' ?>"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-148366650-6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-148366650-6');
    </script>

    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '639059543651633');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=639059543651633&ev=PageView&noscript=1"/></noscript>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->

    <link href="https://fonts.googleapis.com/css?family=IM+Fell+English+SC|IM+Fell+English:400,400i|Jim+Nightshade&display=swap&subset=latin-ext"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@500;600;700;800&display=swap"
        rel="stylesheet">

</head>
<body>


<noscript>

</noscript>
