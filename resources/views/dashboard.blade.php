<x-app-layout>
    <x-slot name="header">Welcome Back</x-slot>
    <div class="py-12 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Redirecting you...</h1>
        <p class="mt-4">One moment please.</p>
        <script>
            setTimeout(() => {
                window.location.href = '/{{ auth()->user()->role->value }}/dashboard';
            }, 1000);
        </script>
    </div>
</x-app-layout>