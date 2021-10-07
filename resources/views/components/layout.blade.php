<!doctype html>

<title>LaTweet</title>
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<body style="font-family: Open Sans, sans-serif" class="bg-gray-200">
    <section class="px-6 py-8">
        <nav class="md:flex md:items-center justify-end">
            <div class="mt-8 md:mt-0">

                @auth
                    <form method="POST" action="/logout" class="ml-6">
                        @csrf

                        <button type="submit" class="bg-blue-500 ml-3 rounded-full text-xs font-semibold text-white uppercase py-3 px-5">Logout</button>
                    </form>
                @endauth
            </div>
        </nav>

        {{ $slot }}

    </section>
</body>
