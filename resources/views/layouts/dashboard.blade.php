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

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Check if the theme is stored in localStorage
            const theme = localStorage.getItem('theme');

            // Apply the theme if it's found
            if (theme === 'dark') {
                document.documentElement.classList.add('dark'); // Enable dark mode
            } else {
                document.documentElement.classList.remove('dark'); // Default to light mode
            }
        });
    </script>
</head>
<body class="h-full dark:bg-slate-900 dark:text-white">
<div id="app" class="h-full">
    <selector-side-nav></selector-side-nav>
</div>
@vite('resources/js/app.js')
</body>
</html>
