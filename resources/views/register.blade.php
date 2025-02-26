<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50 container mx-auto p-4 flex flex-col items-center">
        <h2 class="text-xl py-2">Creating New CritterCaravan User</h2>
        <form action="/store" method="POST">
            @csrf
            <div class="flex flex-col space-y-2 max-w-64">
                <label for="name">Username:</label>
                <input type="text" placeholder="Username" id="name" name="username" class="px-2"/><br>
                <label for="email">Email:</label>
                <input type="text" placeholder="Email" id="email" name="email" class="px-2"/><br>
                <label for="password">Password:</label>
                <input type="password" placeholder="Password" id="password" name="password" class="px-2"/><br>
                <button type="submit" class="rounded-xl bg-black text-white dark:bg-white dark:text-black hover:bg-[#888]">Submit</button>
            </div>
        </form>
        @if (session('data') !== null && session('data')['username'] !== null)
            <p>User "{{session('data')['username']}}" created succesfully.</p>
        @endif
    </body>
</html>
