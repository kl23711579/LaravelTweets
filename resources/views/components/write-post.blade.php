<div class="border border-black">
    <form action="/newpost" method="POST" >
        @csrf
        <div class="m-3">
            <textarea
                name="body"
                class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring"
                rows="4"
                placeholder="What's happening?"
                required></textarea>
            @error('body')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-6 mr-3 flex justify-end">
            <button type="submit"
                    class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
            >
                Post
            </button>
        </div>
    </form>
</div>
