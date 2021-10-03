<x-layout>
    <main class="max-w-6xl mx-auto mt-6 lg:mt-20 space-y-6">
        <div class="lg:grid lg:grid-cols-6">
            <x-identify-card></x-identify-card>

            <div class="col-span-4">
                <x-write-post></x-write-post>

                @foreach($posts as $post)
                    <x-post-card :post="$post"></x-post-card>
                @endforeach

            </div>
        </div>
    </main>
</x-layout>
