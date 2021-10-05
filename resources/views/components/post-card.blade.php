@props(['post'])

<article
    {{ $attributes->merge(['class' => 'border-b border-l border-r border-black duration-300 hover:bg-gray-100 transition-colors']) }}>
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

            <footer class="flex justify-end items-center mt-8">
                <div>
                    <a href="/posts/{{ $post->id }}"
                       class="transition-colors duration-300 text-xs font-semibold bg-gray-200 hover:bg-gray-300 rounded-full py-2 px-5"
                    >Read More</a>
                </div>
            </footer>

        </div>
    </div>
</article>
