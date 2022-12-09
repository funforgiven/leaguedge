<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> test </title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <livewire:styles></livewire:styles>
</head>

<body>

    <x-navbar.navbar></x-navbar.navbar>
    <div class="my-6">
        {{ $content }}
    </div>

    <livewire:scripts></livewire:scripts>
</body>
