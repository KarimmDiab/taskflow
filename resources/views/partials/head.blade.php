<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? 'RYO') ? $title.' - '. 'RYO' : 'RYO' }}
</title>

<link rel="icon" href="images/favicon/favicon.png" sizes="any">
<link rel="icon" type="image/png" href="{{ asset('images/favicon/favicon.png') }}">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=cairo:300,400,500,600,700,800&display=swap" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
