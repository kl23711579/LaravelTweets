@props(['post'])

<article
    class="border-b border-l border-r border-black duration-300 hover:bg-gray-100 transition-colors">
    <div class="py-6 px-5">

        <div class="flex flex-col justify-between">
            <header>

                <div class="mt-4 flex justify-between">
                    <div class="flex">
                        <h1 class="text-3xl">
                            {{ $post->author->name }}
                        </h1>
                        <span class = "mx-3 mt-1 text-gray-400 text-lg">
                            {{ '@' . $post->author->nickname }}
                        </span>
                    </div>


                    <span class="mt-1 text-gray-400 text-lg">
                        Published <time>{{ $post->published_at->diffForHumans() }}</time>
                    </span>
                </div>
            </header>

            <div class="text-sm mt-4">
                {{ $post->body }}
            </div>

        </div>
    </div>
</article>
