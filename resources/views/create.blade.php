<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <a href="/users"><-Return</a>
        <h2>Creating New User</h2>
        <form action="/users/store" method="POST">
            @csrf
            <label for="name">Userame:</label>
            <input type="text" placeholder="Name" id="name" name="username"/><br>
            <label for="email">Email:</label>
            <input type="text" placeholder="Email" id="email" name="email"/><br>
            <label for="password">Password:</label>
            <input type="password" placeholder="Password" id="password" name="password"/><br>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>
