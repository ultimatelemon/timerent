<html class='scroll-smooth'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{--    CSRF Token--}}
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script defer src="https://kit.fontawesome.com/0a918d4211.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
{{--    <link rel="stylesheet"--}}
{{--          href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">--}}
    <title>{{ config('app.name') }}</title>
</head>
<body class="h-full overflow-y-auto flex flex-col">
<div id="app" class="min-h-screen w-full">
    <div class='mx-auto max-w-5xl min-h-screen items-center px-4 '>
        <dashboard-top-bar></dashboard-top-bar>
        <router-view></router-view>
    </div>
</div>
@vite('resources/js/app.js')
</body>
</html>
