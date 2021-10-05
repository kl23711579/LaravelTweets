<x-layout>
    <main class="max-w-6xl mx-auto mt-6 lg:mt-20 space-y-6">
        <div class="lg:grid lg:grid-cols-6">
            <x-identify-card></x-identify-card>

            <div class="col-span-4">
                <x-post-card :post="$post" class="border-t"></x-post-card>
                <div class="border-l border-r border-black p-3">
                    <h1 class="ml-2 text-gray-500 text-base">Replys</h1>
                </div>
                <x-reply-tweet></x-reply-tweet>
            </div>
        </div>
    </main>
</x-layout>
