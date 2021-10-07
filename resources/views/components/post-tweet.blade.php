<div class="mx-2 mb-4 p-2 bg-white rounded-lg">
    <form action="/posts" method="POST" >
        @csrf
        <div class="m-3">
            <textarea
                name="body"
                class="p-2 w-full bg-gray-100 text-gray-700 border rounded-lg focus:outline-none focus:ring"
                rows="4"
                placeholder="What's happening?"
                required></textarea>
            @error('body')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 mr-3 flex justify-end">
            <button type="submit"
                    class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
            >
                Post
            </button>
        </div>
    </form>
</div>
