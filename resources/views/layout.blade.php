<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="application web smartly - Au petit panier Concernant l'association solidarithèque | TFE | jodrick mounga tchamo">
    <meta name="keywords" content="mounga tchamo jodrick, Au petit panier, TFE, heaj, dwt, dwm, web,association">
    <meta name="author" content="Mounga tchamo jodrick">
    <!--TWITTER CARD-->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@jodrick">
    <meta name="twitter:creator" content="@jodrick">
    <meta name="twitter:title" content="TFE | jodrick mounga">
    <meta name="twitter:description" content="smartly - au petit panier Concernant l'association solidarithèque | TFE | jodrick mounga tchamo">
    <meta name="twitter:image" content="https://jodrickmounga.be/projets/TFE/assets/images/meta_partage.PNG">
    <!--OG CARD-->
    <meta property="og:site_name" content="TFE | jodrick mounga">
    <meta property="og:title" content="TFE | jodrick mounga">
    <meta property="og:description" content="smartly au petit panier Concernant l'association solidarithèque  | TFE | jodrick mounga">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://jodrickmounga.be/projets/TFE/">
    <meta property="og:image" content="https://jodrickmounga.be/projets/TFE/assets/images/meta_partage.PNG">
    <meta property="og:image:width" content="1800">
    <meta property="og:image:height" content="945">
    <!-- favicon-->
    <link rel="icon" type="image/svg" href="assets/images/logo.svg">
    <link rel="apple-touch-icon" type="image/svg" href="assets/images/logo.svg">
    <!-- typo -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,400;1,400;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Montserrat:ital,wght@0,100;0,400;1,400;1,900&display=swap" rel="stylesheet">
    <title>@yield('title', "Accueil - au p'tit panier | Travail de fin d'étude - MOUNGA TCHAMO jodrick")</title>
    
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header class="head__container">
        <a class="home" href="{{ route('accueil.show') }}">Accueil</a>

    </header>

    <main>
        
        @yield('content__accueil')
        @yield('content__connexion')
        @yield('content__date')
        @yield('content__welcome')
        @yield('content__verification')
        @yield('content__profil')
    </main>


   
    <footer class="footer container">
        <small class="copyrith">
            ©
            <span class="annee"></span>
            <a href="http://jodrickmounga.be/">jodrick mounga</a>
            |

            <a href="http://dwm.re/">
                <img class="img__logoDwt" src="assets/images/logo-dwt.svg" alt="logo_dwt">
            </a>
        </small>
    </footer>
</body>

</html>