<x-layout>
    <main class="max-w-6xl mx-auto mt-6 lg:mt-10 space-y-6">
        <div class="lg:grid lg:grid-cols-6">
            <div class="mx-2 col-span-2">
                <x-identify-card></x-identify-card>
            </div>


            <div class="col-span-4">
                <x-post-tweet></x-post-tweet>

                @foreach($posts as $post)
                    <x-post-card :post="$post">
                        <x-slot name="footer">
                            <footer class="flex justify-end items-center mt-8">
                                <form method="POST" class="give-star" action={{ '/posts/'.$post->id.'/stars' }}>
                                    @method('PUT')
                                    @csrf
                                    <input type="submit"
                                           id="{{ 'input-star-'.$post->id }}"
                                           class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
                                           value="{{ $post->star->number }}">
                                </form>
                                <div>
                                    <a href="/posts/{{ $post->id }}"
                                       class="transition-colors duration-300 text-xs font-semibold bg-gray-200 hover:bg-gray-300 rounded-full py-2 px-5"
                                    >Read More</a>
                                </div>
                            </footer>
                        </x-slot>
                    </x-post-card>
                @endforeach

            </div>
        </div>
        @if ($posts->count())
            {{ $posts->links() }}
        @endif
    </main>
    @push('scripts')
        <script src="{{ asset('./js/sendstar.js') }}" defer></script>
    @endpush
</x-layout>
