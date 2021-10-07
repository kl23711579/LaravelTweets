<x-layout>
    <main class="max-w-6xl mx-auto mt-6 lg:mt-20 space-y-6">
        <div class="lg:grid lg:grid-cols-6">
            <div class="mx-2 col-span-2">
                <x-identify-card></x-identify-card>
            </div>

            <div class="col-span-4">
                <a href="/posts"
                   class="mb-2 transition-colors duration-300 relative inline-flex items-center text-lg hover:text-blue-500">
                    <svg width="22" height="22" viewBox="0 0 22 22" class="mr-2">
                        <g fill="none" fill-rule="evenodd">
                            <path stroke="#000" stroke-opacity=".012" stroke-width=".5" d="M21 1v20.16H.84V1z">
                            </path>
                            <path class="fill-current"
                                  d="M13.854 7.224l-3.847 3.856 3.847 3.856-1.184 1.184-5.04-5.04 5.04-5.04z">
                            </path>
                        </g>
                    </svg>

                    Back to Posts
                </a>
                <x-post-card :post="$post"><x-slot name="footer"></x-slot></x-post-card>
                <div class="p-3">
                    <h1 class="ml-2 text-gray-500 text-base">Replys</h1>
                </div>
                @foreach($post->reply as $reply)
                    <x-post-card :post="$reply"><x-slot name="footer"></x-slot></x-post-card>
                @endforeach
                <x-reply-tweet :post="$post"></x-reply-tweet>
            </div>
        </div>
    </main>
</x-layout>
