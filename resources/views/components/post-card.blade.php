@props(['post', 'footer'])

<article
    {{ $attributes->merge(['class' => 'bg-white mx-2 mb-4 rounded-lg duration-300 hover:bg-gray-100 transition-colors']) }}>
    <div class="py-4 px-5">
        <div class="flex flex-col justify-between">
            <header>
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold">
                            {{ $post->author->name }}
                        </h1>
                        <span class = "mx-3 text-gray-400 text-sm">
                            {{ '@' . $post->author->nickname }}
                        </span>
                    </div>
                    <span class="mt-1 text-gray-400 text-sm">
                        Published <time>{{ $post->published_at->diffForHumans() }}</time>
                    </span>
                </div>
            </header>

            <div class="text-xl mt-4">
                {{ $post->body }}
            </div>
            {{ $footer }}
        </div>
    </div>
</article>
