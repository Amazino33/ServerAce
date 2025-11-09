<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-2xl shadow-2xl">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                    Check your email!
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    We've sent a verification link to:
                </p>
                <p class="mt-2 text-lg font-bold text-blue-600">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <div class="mt-8 space-y-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        Click the link in your email to activate your account. It expires in 60 minutes.
                    </p>
                </div>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition">
                        Resend Verification Email
                    </button>
                </form>

                <div class="text-center">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Log out
                        </button>
                    </form>
                </div>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-center">
                    A new verification link has been sent to your email!
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>