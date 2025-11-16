<x-app-layout>
    <x-slot name="header">Welcome Back</x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"></path>
                    </svg>
                </div>

                <h1 class="text-2xl font-semibold text-gray-800 mt-4">Redirecting you to your dashboard…</h1>
                <p class="text-sm text-gray-600 mt-3">You will be taken to your personalized dashboard shortly.</p>

                <div class="mt-6">
                    <a href="/{{ auth()->user()->role->value }}/dashboard"
                       class="inline-flex items-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                        Go now
                    </a>
                    <button onclick="location.reload()"
                            class="ml-3 inline-flex items-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition">
                        Refresh
                    </button>
                </div>

                <p class="text-xs text-gray-400 mt-4">If you are not redirected automatically, use the button above.</p>

                <script>
                    // small delay to allow the page to render before redirect
                    setTimeout(() => {
                        window.location.href = '/{{ auth()->user()->role->value }}/dashboard';
                    }, 900);
                </script>
            </div>
        </div>
    </div>
</x-app-layout>