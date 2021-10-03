<article
    class="col-span-2">
    <div class="px-5">
        <div>
            <img src="./images/illustration-1.png" alt="Blog Post illustration" class="rounded-xl">
        </div>

        <div class="mt-8 flex flex-col ">
            <header>

                <div class="mt-4">
                    <h1 class="text-3xl">
                        {{ auth()->user()->name }}
                    </h1>

                    <span class="mt-2 block text-gray-400">
                        {{ '@' . auth()->user()->twitter_id }}
                    </span>
                </div>
            </header>

        </div>
    </div>
</article>
