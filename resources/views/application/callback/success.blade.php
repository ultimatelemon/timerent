<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Betaling geslaagd</title>
</head>
<body class="h-full">
<main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8">
    <div class="text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl mb-4 pb-4">Betaling is gelukt 🥳</h1>
        <a href="/" class="mt-6 text-base leading-7 text-gray-600">Keer terug naar Timerent &rarr;</a>
        {{--        <div class="mt-10 flex items-center justify-center gap-x-6 group">--}}
        {{--            <a href="/" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Terug naar Rijscholenapp</a>--}}
        {{--            <a href="/" class="text-sm font-semibold text-gray-900 group">Inloggen op je rijschool<span aria-hidden="true">&rarr;</span></a>--}}
        {{--        </div>--}}
    </div>
</main>
</body>
</html>
