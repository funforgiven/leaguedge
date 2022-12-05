<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> test </title>
    @vite('resources/css/app.css')
</head>

<body class="dark:bg-gray-900">

    <x-navbar.navbar></x-navbar.navbar>
    {{ $content }}

    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
</body>

<script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>