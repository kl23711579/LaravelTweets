<x-layout>
    <section class="px-6 py-8">
        <main class="max-w-lg mx-auto mt-10 bg-gray-100 border border-gray p-6 rounded-xl">
            <h1 class="text-center font-bold text-xl">Login!</h1>
            <div class="flex items-canter">
                <a href="{{ route('login.twitter') }}"
                   type="button"
                   class="py-2 px-4 mx-auto mt-6 font-semibold rounded-lg shadow-md text-white bg-blue-500 hover:bg-blue-700"> Login With Twitter</a>
            </div>
        </main>
    </section>
</x-layout>
