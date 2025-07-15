<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Members') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Members Table</h3>

                    <!-- Tabel Members -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2">ID</th>
                                    <th class="border border-gray-300 px-4 py-2">Name</th>
                                    <th class="border border-gray-300 px-4 py-2">Telephone</th>
                                    <th class="border border-gray-300 px-4 py-2">Point</th>
                                    <th class="border border-gray-300 px-4 py-2">Status</th>
                                    <th class="border border-gray-300 px-4 py-2">Created At</th>
                                    @if (auth()->user()->role === 'admin')
                                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="border border-gray-300 px-4 py-2">{{ $member->id_member }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $member->name }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $member->telephone }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $member->point }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <span
                                                class="px-2 py-1 rounded text-white {{ $member->status == 'active' ? 'bg-green-600' : 'bg-red-600' }}">
                                                {{ ucfirst($member->status) }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            {{ $member->created_at->format('Y-m-d') }}</td>
                                        @if (auth()->user()->role === 'admin')
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="{{ route('members.edit', $member->id_member) }}"
                                                class="px-2 py-1 bg-blue-600 text-white rounded">Edit</a>
                                            <form action="{{ route('members.destroy', $member->id_member) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Akhir Tabel Members -->

                    <!-- Form Tambah Member -->
                    <div class="mb-6 mt-6">
                        <h3 class="text-lg font-semibold">Add Member</h3>
                        <form action="{{ route('members.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium">Name</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="mb-4">
                                <label for="telephone" class="block text-sm font-medium">Telephone</label>
                                <input type="text" name="telephone" id="telephone" required
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                                @if ($errors->has('telephone'))
                                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('telephone') }}</p>
                                @endif

                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Submit</button>
                        </form>
                    </div>
                    <!-- Akhir Form Tambah Member -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
