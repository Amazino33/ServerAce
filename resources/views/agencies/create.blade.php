<x-app-layout>
    <x-slot name="title">
        Create Agency
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-brand-dark overflow-hidden shadow-sm sm:rounded-lg p-8">

                <h2 class="text-2xl font-bold text-gray-900 dark:text-brand-light mb-8">Create New Agency</h2>

                @error('agency_limit')
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $message }}
                    </div>
                @enderror

                <form method="POST" action="{{ route('agencies.store') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Agency Name <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-accent focus:ring-brand-accent dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                               placeholder="e.g. Pixel Perfect Studio"
                               required>

                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
                        <textarea   name="description"
                                    id="description"
                                    rows="4"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-brand-accent focus:ring-brand-accent dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                                    value="{{ old('description') }}"></textarea>

                        @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-primary-500 py-2 px-4 text-sm font-medium text-black shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand-accent focus:ring-offset-2">
                            Create Agency
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
