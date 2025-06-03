<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Cashier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-white">Name</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" required
                                   class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-white">Email</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" required
                                   class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-white">Password</label>
                            <input type="password" name="password" id="password"
                                   class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-white">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
            <label class="block text-sm font-medium text-white">Photo</label>
            <input type="file" name="photo" id="imageUpload" accept="image/*"
                   class="mt-1 block w-full px-3 py-2 bg-gray-700 text-white rounded">
            <div id="imagePreview" class="mt-2">
                @if ($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" class="w-40 rounded shadow-md">
                @else
                    <span class="text-gray-400">No image selected</span>
                @endif
            </div>
            @if ($user->photo)
    <div class="mt-2 flex items-center space-x-2">
        <input type="checkbox" name="remove_photo" id="remove_photo" class="text-red-600">
        <label for="remove_photo" class="text-sm text-white">Delete profile picture</label>
    </div>
@endif

        </div>

                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Update Cashier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    const input = document.getElementById('imageUpload');
    const preview = document.getElementById('imagePreview');

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            preview.innerHTML = `
                <div class="relative mt-2 w-40">
                    <img src="${URL.createObjectURL(file)}" class="rounded shadow-md">
                    <button onclick="removeImage()" class="absolute top-0 right-0 bg-red-600 text-white px-2 py-1 rounded-full">×</button>
                </div>
            `;
        }
    });

    function removeImage() {
        input.value = '';
        preview.innerHTML = '<span class="text-gray-400">No image selected</span>';
    }
</script>

</x-app-layout>