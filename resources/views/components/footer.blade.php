<footer class="bg-white border-t">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between text-sm text-gray-600">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <div class="mt-4 md:mt-0 flex space-x-4">
            <a href="{{ route('privacy.policy') ?? '#' }}" class="hover:text-gray-800">Privacy Policy</a>
            <a href="{{ route('terms.of.service') ?? '#' }}" class="hover:text-gray-800">Terms of Service</a>
            <a href="/contact" class="hover:text-gray-800">Contact</a>
        </div>
    </div>
</footer>