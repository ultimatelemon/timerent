<html class='scroll-smooth h-full bg-white'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <!-- Primary Meta Tags -->
    <title>{{ env('APP_NAME')  }} - Eenvoudig reserveren</title>
    <meta name="title" content="Timerent - Eenvoudig boeken" />
    <meta name="description" content="Boek eenvoudig via timerent.nl" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://timerentapp.nl/" />
    <meta property="og:title" content="Timerent - Eenvoudig boeken" />
    <meta property="og:description" content="Boek eenvoudig via timerent.nl" />
    <meta property="og:image" content="https://ultimatelemon.eu/_next/image?url=https%3A%2F%2Fcdn.ultimatelemon.eu%2Ftransparent-black-banner.png&w=256&q=75" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://timerentapp.nl/" />
    <meta property="twitter:title" content="Timerent - Eenvoudig boeken" />
    <meta property="twitter:description" content="Boek eenvoudig via timerent.nl" />
    <meta property="twitter:image" content="https://ultimatelemon.eu/_next/image?url=https%3A%2F%2Fcdn.ultimatelemon.eu%2Ftransparent-black-banner.png&w=256&q=75" />

    <!-- Meta Tags Generated with https://metatags.io -->

    {{--    CSRF Token--}}
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script defer src="https://kit.fontawesome.com/0a918d4211.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
{{--    <link rel="stylesheet"--}}
{{--          href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">--}}
</head>
<body class="h-full">
<div id="app" class="h-full">
    <side-bar></side-bar>
</div>
@vite('resources/js/app.js')
</body>
</html>
