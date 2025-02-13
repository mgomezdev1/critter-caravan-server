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
        <h2>List Of Users</h2>
        <p><a href="/users/create">Create new</a></p>
        @if (sizeof($users) == 0)
            <p>No users were found.</p>
        @else
            @foreach ($users as $user)
                <p>-{{ $user->id }}. {{ $user->username }} <a href="/users/edit/{{ $user->id }}">Edit</a> | <a href="/users/delete/{{ $user->id }}">Delete</a></p>
            @endforeach
        @endif
    </body>
</html>
