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

    <div class="flex flex-col items-center justify-center space-y-4 mt-4">
        <div class="max-w-7xl w-full flex items-center justify-center space-x-4">
            <form action="/scrape" method="post" class="w-full flex space-x-4">
                @csrf
                <input
                    autofocus
                    type="text"
                    name="url"
                    placeholder="Enter Inzoi's Gallery URL"
                    class="flex-grow p-2 border border-gray-300 rounded ring-2 ring-gray-300 focus:ring-blue-500 focus:outline-none"
                />
                <button
                    type="submit"
                    class="p-2 bg-blue-500 text-white rounded hover:bg-blue-700 cursor-pointer"
                >
                    Scrape
                </button>
            </form>
        </div>        

        <!-- Error message -->
        @if ($errors->any())
            <div class="text-red-500 font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Success message -->
        @if (session('success'))
            <div class="text-green-500 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Failure message -->
        @if (session('failure'))
            <div class="text-red-500 font-bold">
                {{ session('failure') }}
            </div>
        @endif

        <!-- List of scrapped data -->
        @if ($galleries->count() > 0)
            <hr class="w-7xl" />
            <div class="overflow-y">
                <table class="w-7xl text-center">
                    <thead>
                        <tr class="bg-gray-300">
                            <th class="border border-gray-300">#</th>
                            <th class="border border-gray-300">Creator</th>
                            <th class="border border-gray-300">Title</th>
                            <th class="border border-gray-300">Images</th>
                            <th class="border border-gray-300">Downloads</th>
                            <th class="border border-gray-300">Likes</th>
                            <th class="border border-gray-300">Status</th>
                            <th class="border border-gray-300">Creation</th>
                            <th class="border border-gray-300">Scraped At</th>
                            <th class="border border-gray-300"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($galleries as $gallery)
                            <tr>
                                <td class="border border-gray-300 flex justify-center">
                                    <img class="w-16 h-16 rounded-full" src="{{ $gallery->images[0]->url }}" alt="{{ $gallery->title }}"  />
                                </td>
                                <td class="border border-gray-300 font-semibold hover:underline text-blue-800">
                                    <a target="_blank" href="https://canvas.playinzoi.com/en-US/profile/{{ $gallery->creator->profile_id }}">{{ $gallery->creator->name }}</a>
                                </td>
                                <td class="border border-gray-300 font-semibold hover:underline text-blue-800">
                                    <a target="_blank" href="{{ $gallery->url }}">{{ \Str::title($gallery->title) }}</a>
                                </td>
                                <td class="border border-gray-300">{{ $gallery->images()->count() }}</td>
                                <td class="border border-gray-300">{{ $gallery->downloads }}</td>
                                <td class="border border-gray-300">{{ $gallery->likes }}</td>
                                <td class="border border-gray-300">
                                    @if ($gallery->published_at)
                                        <span class="bg-green-700 p-1 rounded text-gray-100">Published</span>
                                    @else
                                        <span class="bg-gray-500 p-1 rounded text-gray-100">Draft</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300">{{ $gallery->creation_date->format('Y-m-d') }}</td>
                                <td class="border border-gray-300">{{ $gallery->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="border border-gray-300">
                                    <a href="{{ route('gallery.show', $gallery->id) }}">🔎</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="w-7xl">
                {{ $galleries->links() }}
            </div>
        @endif

    </div>
</body>

</html>
