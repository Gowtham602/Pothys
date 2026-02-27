<x-app-layout>
    <div class="p-6">

        @if(session('success'))
            <div class="bg-green-200 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        
@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <h2 class="text-xl font-bold mb-4">Upload Image</h2>

        <form method="POST" action="{{ route('image.upload') }}" enctype="multipart/form-data">
            @csrf

            <input type="text" name="image_name" placeholder="Image Name"
                class="border p-2 mr-2" required>

            <input type="file" name="image"
                class="border p-2 mr-2" required>

            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded">
                Upload
            </button>
        </form>

        <hr class="my-6">

        <h2 class="text-xl font-bold mb-4">Your Images</h2>

        <table class="w-full border">
            <tr class="bg-gray-200">
                <th class="p-2">Name</th>
                <th class="p-2">Short URL</th>
                <th class="p-2">Action</th>
            </tr>

            @foreach($images as $image)
                <tr>
                    <td class="p-2">{{ $image->image_name }}</td>
                    <td class="p-2">
                        {{ url('/s/'.$image->short_code) }}
                    </td>
                    <td class="p-2">
                        <a href="{{ url('/s/'.$image->short_code) }}"
                           target="_blank"
                           class="text-blue-600">
                            Open
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
</x-app-layout>