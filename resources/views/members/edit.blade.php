<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Edit Member</h3>

                    <!-- Form Edit Member -->
                    <form action="{{ route('members.update', $member->id_member) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- ID (Disabled) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Member ID</label>
                            <input type="text" value="{{ $member->id_member }}" disabled
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium">Name</label>
                            <input type="text" name="name" id="name" value="{{ $member->name }}" required
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Telephone -->
                        <div class="mb-4">
                            <label for="telephone" class="block text-sm font-medium">Telephone</label>
                            <input type="text" name="telephone" id="telephone" value="{{ $member->telephone }}" required
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                                @if ($errors->has('telephone'))
    <p class="text-red-500 text-sm mt-1">{{ $errors->first('telephone') }}</p>
@endif
                        </div>

                        <!-- Point -->
                        <div class="mb-4">
                            <label for="point" class="block text-sm font-medium">Point</label>
                            <input type="number" name="point" id="point" value="{{ $member->point }}" required
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                                <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="non-active" {{ $member->status == 'non-active' ? 'selected' : '' }}>Non-Active</option>
                            </select>
                        </div>

                        <!-- Tombol Update -->
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                    </form>
                    <!-- Akhir Form Edit Member -->

                    <!-- Tombol Delete -->
                    <form action="{{ route('members.destroy', $member->id_member) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    <!-- Akhir Tombol Delete -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
