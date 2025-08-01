<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users List') }}
        </h2>
    </x-slot>


    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Users Table</h3>

                    @if (session('alert'))
                        <div class="bg-gray-600 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Alert: </strong>
                            <span class="block sm:inline">{{ session('alert') }}</span>
                        </div>
                    @endif


                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2">ID</th>
                                    <th class="border border-gray-300 px-4 py-2">Name</th>
                                    <th class="border border-gray-300 px-4 py-2">Email</th>
                                    <th class="border border-gray-300 px-4 py-2">Role</th>
                                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                                        <td class="border border-gray-300 px-4 py-2 flex items-center gap-2">
                                            @if ($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo"
                                                    class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                {!! Avatar::create($user->name)->setShape('circle')->setDimension(32)->toSvg() !!}
                                            @endif
                                            <span>{{ $user->name }}</span>
                                        </td>


                                        <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                                        <td class="border border-gray-300 px-3">
                                            @if ($user->role == 'admin')
                                                <span
                                                    class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                                                    Admin
                                                </span>
                                            @else
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">
                                                    Cashier
                                                </span>
                                            @endif
                                        </td>


                                        <td class="border border-gray-300 px-4 py-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.edit', $user->id) }}"
                                                class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                Edit
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this user?');">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Form Tambah Cashier -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold">Add Users</h3>
                        <form method="POST" action="{{ route('register') }}" class="mt-4"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-white">Name</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-white">Email</label>
                                <input type="email" name="email" id="email" required
                                    value="{{ old('email') }}"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-white">Password</label>
                                <input type="password" name="password" id="password" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-white">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                @error('password_confirmation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="role" class="block text-sm font-medium text-white">Role</label>
                                <select name="role" id="role" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-white bg-gray-700">
                                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>

                                </select>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white">Photo</label>
                                <input type="file" name="photo" id="imageUpload" accept="image/*"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm bg-gray-700 text-white">
                                <div id="imagePreview" class="mt-2 text-gray-400">No image selected</div>
                            </div>

                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Register
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('imageUpload');
        const preview = document.getElementById('imagePreview');

        input.addEventListener('change', function() {
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
