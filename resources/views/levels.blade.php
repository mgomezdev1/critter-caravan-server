<!DOCTYPE html>
@php
    $sortOptions = [
        "name" => "Level Name",
        "upload_date" => "Creation Date",
        "modified_date" => "Last Edit Date"
    ];
    $verificationLevels = [
        -1 => ["Any", "#ffffff"],
        0 => ["Unverified", "#ff6666"],
        1 => ["Verified", "#00ff00"],
        2 => ["Ranked", "#00ff00"],
        3 => ["Featured", "#ffcc00"],
        4 => ["Official", "#00ffff"]
    ];
@endphp
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
        <h2 class="text-2xl py-2 dark:text-white">Critter Caravan Level Browser</h2>

        <form action="/search" method="GET">
            @csrf
            <div class="flex flex-col gap-8 p-2 items-center">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="name">Name:</label>
                        <input type="text" placeholder="Name" id="name" name="name"
                        @if (request()->has('name'))
                            value="{{request()->query('name')}}"
                        @endif
                        class="px-2 border-2 dark:bg-black dark:text-white"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="author">Author Name:</label>
                        <input type="text" placeholder="Author Name" id="author" name="author"
                        @if (request()->has('author'))
                            value="{{request()->query('author')}}"
                        @endif
                        class="px-2 border-2 dark:bg-black dark:text-white"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="category">Category:</label>
                        <input type="text" placeholder="Category" id="category" name="category"
                        @if (request()->has('category'))
                            value="{{request()->query('category')}}"
                        @endif
                        class="px-2 border-2 dark:bg-black dark:text-white"/>
                    </div>
                    <div class="flex flex-col gap-2 items-center">
                        <label for="min_verification">Verification:</label>
                        <div class="flex flex-row gap-2">
                            <select id="min_verification" name="min_verification" class="px-2 border-2 dark:bg-black dark:text-white">
                                @foreach ($verificationLevels as $key => [$label, $_color])
                                    <option value="{{$key}}"
                                    @if (request()->has('min_verification') && request()->query('min_verification') == $key)
                                        selected
                                    @endif
                                    >{{$label}}</option>
                                @endforeach
                            </select>
                            <div>-</div>
                            <select id="max_verification" name="max_verification" class="px-2 border-2 dark:bg-black dark:text-white">
                                @foreach ($verificationLevels as $key => [$label, $_color])
                                    <option value="{{$key}}"
                                    @if (request()->has('max_verification') && request()->query('max_verification') == $key)
                                        selected
                                    @endif
                                    >{{$label}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="sort">Sort:</label>
                    <div class="flex flex-row gap-2 items-center">
                        <select id="sort" name="sort" class="px-2 border-2 dark:bg-black dark:text-white">
                            @foreach ($sortOptions as $key => $label)
                                <option value="{{$key}}"
                                @if (request()->has('sort') && request()->query('sort') == $key)
                                    selected
                                @endif
                                >
                                {{$label}}</option>
                            @endforeach
                        </select>
                        <label for="sort_asc">Asc</label>
                        <input type="checkbox" id="sort_asc" name="sort_asc"
                        @if (request()->has('sort_asc'))
                            checked="{{request()->query('sort_asc')}}"
                        @endif
                        class="px-2 border-2 dark:bg-black dark:text-white"/>
                    </div>
                </div>
                <button type="submit" class="rounded-xl px-2 border-2 dark:bg-black dark:text-white hover:bg-[#888] max-w-64">Search</button>
            </div>
        </form>
        <div class="m-4">Showing {{$data['from']}} to {{$data['to']}} of {{$data['total']}} total results</div>
        <div class="flex">
            @foreach ($data['data'] as $level)
                <div
                    style="text-shadow: -1px -1px 3px #000, 1px -1px 3px #000, -1px 1px 3px #000, 1px 1px 3px #000"
                    class="rounded-xl mx-2 border-2 relative overflow-hidden w-[480px] h-[270px] flex flex-col justify-between
                    @if ($level['verification_level'] == 2)
                        border-[#ffcc00]
                    @endif
                    text-white hover:bg-[#8885]"
                >
                    <h2 class="text-xl p-2 dark:text-white">{{$level['name']}}</h2>
                    <img class="object-cover absolute w-full h-full left-0 top-0 -z-10" src="                    
                        @if ($level['thumbnail'] != '')
                            {{$level['thumbnail']}}
                        @else
                            https://placehold.co/480x270
                        @endif
                    " alt="level {{$level['id']}} thumbnail">
                    <div class="p-2 flex flex-col gap-2">
                        <div>ID: {{$level['id']}}</div>
                        <div>Author ID: {{$level['author_id']}}</div>
                        @if ($level['category'] != '')
                            <div>Category: {{$level['category']}}</div>
                        @endif
                        <div>
                        @switch($level['private'])
                            @case(0)
                                Public
                                @break
                            @case(1)
                                Private
                                @break
                        @endswitch
                        </div>
                        <span style="color:{{$verificationLevels[$level['verification_level']][1]}}">{{$verificationLevels[$level['verification_level']][0]}}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 flex flex-col items-center">
            <div>Page {{$data['current_page']}} of {{$data['last_page']}}</div>
            <div class="mt-4 flex flex-row items-center gap-2">
                <a href="{{$data['first_page_url']}}" class="rounded-xl px-2 py-1 dark:bg-black 
                @if ($data['current_page'] == '1')
                    text-gray pointer-events-none">
                @else
                    border-2 dark:text-white hover:bg-[#888]">
                @endif
                &lt;&lt;</a>
                <a href="{{$data['prev_page_url']}}" class="rounded-xl px-2 py-1 dark:bg-black 
                @if (!$data['prev_page_url'])
                    text-gray pointer-events-none">
                @else
                    border-2 dark:text-white hover:bg-[#888]">
                @endif
                &lt;</a>
                <div class="mx-2">{{$data['current_page']}}</div>
                <a href="{{$data['next_page_url']}}" class="rounded-xl px-2 py-1 dark:bg-black 
                @if (!$data['next_page_url'])
                    text-gray pointer-events-none">
                @else
                    border-2 dark:text-white hover:bg-[#888]">
                @endif
                &gt;</a>
                <a href="{{$data['last_page_url']}}" class="rounded-xl px-2 py-1 dark:bg-black 
                @if ($data['current_page'] == $data['last_page'])
                    text-gray pointer-events-none">
                @else
                    border-2 dark:text-white hover:bg-[#888]">
                @endif
                &gt;&gt;</a>
            </div>
        </div>
    </body>
</html>
