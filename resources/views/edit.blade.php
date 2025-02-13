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
        <h2>Updating User #{{ $user->id }}</h2>
        <form action="/users/update" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}"/><br>
            <label for="name">Userame:</label>
            <input type="text" placeholder="Name" id="name" name="name" value="{{ $user->username }}"/><br>
            <label for="email">Email:</label>
            <input type="text" placeholder="Email" id="email" name="email" value="{{ $user->email }}"/><br>
            <label for="password">New password:</label>
            <input type="password" placeholder="New password" id="password" name="password"/><br>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>
