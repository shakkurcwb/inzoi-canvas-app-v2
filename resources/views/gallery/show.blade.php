<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <nav class="bg-blue-500 text-white p-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold">Zoi Scraper</a>
        </div>
    </nav>

    <div class="flex flex-col items-center justify-center space-y-2">
        <!-- Success message -->
        @if (session('success'))
        <div class="text-green-500 font-bold">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error message -->
        @if ($errors->any())
            <div class="text-red-500 font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Failure message -->
        @if (session('failure'))
            <div class="text-red-500 font-bold">
                {{ session('failure') }}
            </div>
        @endif

        <h1 class="text-3xl font-bold">{{ \Str::title($gallery->title) }} by {{ $gallery->creator->name }}</h1>

        @if ($gallery->description)
            <p class="text-lg">{{ $gallery->description }}</p>
        @endif

        <table class="w-4xl text-center">
            <tbody>
                <tr>
                    <td class="border border-gray-300">Creator</td>
                    <td class="border border-gray-300 font-semibold hover:underline text-blue-800">
                        <a target="_blank"
                            href="https://canvas.playinzoi.com/en-US/profile/{{ $gallery->creator->profile_id }}">{{ $gallery->creator->name }}</a>
                    </td>
                </tr>
                <tr>
                    <td class="border border-gray-300">Title</td>
                    <td class="border border-gray-300 font-semibold hover:underline text-blue-800">
                        <a target="_blank" href="{{ $gallery->url }}">{{ \Str::title($gallery->title) }}</a>
                    </td>
                </tr>
                @if (false)
                    <tr>
                        <td class="border border-gray-300">Description</td>
                        <td class="border border-gray-300 font-semibold">{{ $gallery->description }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="border border-gray-300">Images</td>
                    <td class="border border-gray-300 font-semibold">{{ $gallery->images->count() }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300">Downloads</td>
                    <td class="border border-gray-300 font-semibold">{{ $gallery->downloads }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300">Likes</td>
                    <td class="border border-gray-300 font-semibold">{{ $gallery->likes }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-300">Status</td>
                    <td class="border border-gray-300 font-semibold">
                        @if ($gallery->published_at)
                            <span class="text-green-700">Published ✅</span>
                        @else
                            <span class="text-gray-500">Draft 📝</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="border border-gray-300">Creation</td>
                    <td class="border border-gray-300 font-semibold">{{ $gallery->creation_date->format('Y-m-d') }}
                    </td>
                </tr>
                @if ($gallery->published_at)
                    <tr>
                        <td class="border border-gray-300">Published At</td>
                        <td class="border border-gray-300 font-semibold">
                            {{ $gallery->published_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="border border-gray-300">Scraped At</td>
                    <td class="border border-gray-300 font-semibold">{{ $gallery->created_at->format('Y-m-d H:i:s') }}
                    </td>
                </tr>
            </tbody>
        </table>

        @foreach ($gallery->images as $image)
            <img src="{{ $image->url }}" alt="{{ $image->title }}" class="w-4xl" />
        @endforeach

        <hr class="w-4xl" />

        <form action="{{ route('creator.update', $gallery->creator->id) }}" method="POST"
            class="max-w-4xl w-full flex flex-col space-y-4 bg-gray-300 p-4 rounded">
            @csrf
            @method('PATCH')
            <label for="instagram" class="text-start border-b-2">Creator's Instagram</label>
            <input type="text" name="instagram" value="{{ $gallery->creator->instagram }}" class="flex-grow p-2 bg-slate-100 border border-white rounded ring-2 ring-white focus:ring-blue-500 focus:outline-none" />
            <button
                type="submit"
                class="p-2 bg-blue-500 text-white rounded hover:bg-blue-700 cursor-pointer"
            >Update Instagram</button>
        </form>

        <hr class="w-4xl" />

        @if (!$gallery->published_at)
            <form action="{{ route('gallery.update', $gallery) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="published_at" value="{{ now() }}" />
                <button type="submit"
                    class="bg-green-700 hover:bg-green-600 text-white p-2 rounded cursor-pointer">Mark as
                    Published</button>
            </form>

            <hr class="w-4xl" />
        @endif

        @if ($gallery->published_at)
            <form action="{{ route('gallery.update', $gallery) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="published_at" value="" />
                <button type="submit" class="bg-red-700 hover:bg-red-600 text-white p-2 rounded cursor-pointer">Mark as
                    Draft</button>
            </form>

            <hr class="w-4xl" />
        @endif

        <p class="text-start border-b-2">Standard Version
            <a href="javascript:void(0)" onclick="copyStandard()">📋</a>
        </p>
        <pre class="w-4xl text-start">
            🎀 {{ \Str::title($gallery->title) }} by {{ $gallery->creator->name }}. 🎀

            📅 Live on inZOI since {{ $gallery->creation_date->format('Y-m-d') }}.

            📥 Downloads: {{ $gallery->downloads }}
            ❤️ Likes: {{ $gallery->likes }}

            👉 Follow {{ $gallery->creator->name }} on Canvas to get more amazing galleries. 🚀

            💬 Which {{ $gallery->creator->name }} preset would you love to see next? Drop a comment below! 👇

            #inzoi #playinzoi #canvas #gallery #{{ $gallery->creator->name }}
        </pre>

        <hr class="w-4xl" />

        <p class="text-start border-b-2">Alternative Version
            <a href="javascript:void(0)" onclick="copyAlternative()">📋</a>
        </p>
        <pre class="w-4xl text-start">
            🔥 {{ \Str::title($gallery->title) }} by {{ $gallery->creator->name }}. 🔥

            📅 Available on inZOI since {{ $gallery->creation_date->format('Y-m-d') }}.

            📥 Downloads: {{ $gallery->downloads }}
            ❤️ Likes: {{ $gallery->likes }}

            👉 Discover more stunning galleries by {{ $gallery->creator->name }} on Canvas. Don’t miss out! 🎨

            💬 Love this gallery? Want to see your gallery featured here? Let us know in the comments! 👇

            #inzoi #playinzoi #canvas #gallery #{{ $gallery->creator->name }}
        </pre>
    </div>

    <script>
        function copyStandard() {
            let text = `🎀 {{ \Str::title($gallery->title) }} by {{ $gallery->creator->name }}. 🎀\n\n`;
            text += `📅 Live on inZOI since {{ $gallery->creation_date->format('Y-m-d') }}.\n\n`;
            text += `📥 Downloads: {{ $gallery->downloads }}\n`;
            text += `❤️ Likes: {{ $gallery->likes }}\n\n`;
            text += `👉 Follow {{ $gallery->creator->name }} on Canvas to get more amazing galleries. 🚀\n\n`;
            text +=
                `💬 Which {{ $gallery->creator->name }} preset would you love to see next? Drop a comment below! 👇\n\n`;
            text += `#inzoi #playinzoi #canvas #gallery #{{ $gallery->creator->name }}`;

            navigator.clipboard.writeText(text);
        };

        function copyAlternative() {
            let text = `🔥 {{ \Str::title($gallery->title) }} by {{ $gallery->creator->name }}. 🔥\n\n`;
            text += `📅 Available on inZOI since {{ $gallery->creation_date->format('Y-m-d') }}.\n\n`;
            text += `📥 Downloads: {{ $gallery->downloads }}\n`;
            text += `❤️ Likes: {{ $gallery->likes }}\n\n`;
            text +=
                `👉 Discover more stunning galleries by {{ $gallery->creator->name }} on Canvas. Don’t miss out! 🎨\n\n`;
            text += `💬 Love this gallery? Want to see your gallery featured here? Let us know in the comments! 👇\n\n`;
            text += `#inzoi #playinzoi #canvas #gallery #{{ $gallery->creator->name }}`;

            navigator.clipboard.writeText(text);
        };
    </script>
</body>

</html>
