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
        <h2 class="text-xl py-2 dark:text-white">Create a New Critter Caravan User</h2>
        <form action="/store" method="POST">
            @csrf
            <div class="flex flex-col space-y-2 max-w-64">
                <label for="name">Username:</label>
                <input type="text" placeholder="Username" id="name" name="username"
                @isset (session('data')['response']['errors'], session('data')['request']['username'])
                    value="{{session('data')['request']['username']}}"
                @endisset
                class="px-2 border-2 dark:bg-black dark:text-white"/>
                @isset (session('data')['response']['errors']['username'])
                    <p class="text-[#ff0000]">{{implode('\n', session('data')['response']['errors']['username'])}}</p>
                @endisset
                <br><label for="email">Email:</label>
                <input type="text" placeholder="Email" id="email" name="email"
                @isset (session('data')['response']['errors'], session('data')['request']['email'])
                    value="{{session('data')['request']['email']}}"
                @endisset
                class="px-2 border-2 dark:bg-black dark:text-white"/>
                @isset (session('data')['response']['errors']['email'])
                    <p class="text-[#ff0000]">{{implode('\n', session('data')['response']['errors']['email'])}}</p>
                @endisset
                <br><label for="password">Password:</label>
                <input type="password" placeholder="Password" id="password" name="password" class="px-2 border-2 dark:bg-black dark:text-white"/>
                @isset (session('data')['response']['errors']['password'])
                    <p class="text-[#ff0000]">{{implode('\n', session('data')['response']['errors']['password'])}}</p>
                @endisset
                <br><button type="submit" class="rounded-xl px-2 border-2 dark:bg-black dark:text-white hover:bg-[#888]">Submit</button>
            </div>
        </form>
        @isset(session('data')['response']['username'])
            <div><br><p class="text-[#00ff00]">User "{{session('data')['response']['username']}}" created succesfully.</p></div>
        @endisset
    </body>
</html>
