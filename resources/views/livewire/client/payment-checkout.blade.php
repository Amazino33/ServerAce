<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" x-transition
            @keydown.escape.window="$wire.close()">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="$wire.close()"></div>

            <!-- Modal -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6" x-show="show"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" @click.stop>
                    <!-- Close Button -->
                    <button wire:click="close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Complete Payment</h2>
                        <p class="mt-2 text-sm text-gray-600">Secure payment processed by Stripe</p>
                        @if($application)
                            <p class="mt-1 text-sm text-gray-500">
                                Hiring: <span class="font-medium">{{ $application->freelancer->name }}</span>
                            </p>
                        @endif
                    </div>

                    <!-- Payment Summary -->
                    @if($paymentDetails)
                        <div class="mb-6 bg-gray-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Amount</span>
                                    <span class="font-medium">${{ number_format($paymentDetails['amount'], 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Platform Fee (10%)</span>
                                    <span class="font-medium">${{ number_format($paymentDetails['platform_fee'], 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 mt-2">
                                    <div class="flex justify-between">
                                        <span class="font-semibold">Total Charge</span>
                                        <span
                                            class="font-bold text-lg">${{ number_format($paymentDetails['amount'], 2) }}</span>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-2">
                                    Freelancer receives: ${{ number_format($paymentDetails['freelancer_amount'], 2) }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if($error)
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            {{ $error }}
                        </div>
                    @endif

                    <!-- === STRIPE PAYMENT FORM (FIXED VERSION) === -->
                    @if($clientSecret && !$error)
                                    <div x-data="paymentForm({
                                        clientSecret: {{ Js::from($clientSecret) }},
                                        stripeKey: {{ Js::from(config('cashier.key')) }},
                                        userName: {{ Js::from(auth()->user()?->name ?? '') }},
                                        userEmail: {{ Js::from(auth()->user()?->email ?? '') }}
                                    })"
                                     wire:ignore.self 
                                     {{-- <!-- CRITICAL: Prevents Livewire from destroying Stripe Elements --> --}}
                                        class="space-y-6"
                                        >
                                        <!-- Card Element -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Details</label>
                                            <div id="card-element" class="border border-gray-300 rounded-lg p-3 bg-white min-h-[50px]">
                                            </div>
                                            <div id="card-errors" role="alert" class="text-red-600 text-sm mt-2"></div>
                                        </div>

                                        <!-- Security Note -->
                                        <div class="flex items-start text-xs text-gray-500">
                                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span>Your payment is secured by Stripe. Funds will be held in escrow until you approve the
                                                completed work.</span>
                                        </div>

                                        <!-- Pay Button -->
                                        <button type="button" @click="handlePayment()" :disabled="processing"
                                            class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors flex items-center justify-center">
                                            <span x-show="!processing">
                                                Pay ${{ number_format($paymentDetails['amount'] ?? 0, 2) }} Securely
                                            </span>
                                            <span x-show="processing" class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                Processing...
                                            </span>
                                        </button>

                                        <p class="mt-3 text-xs text-center text-gray-500">
                                            By clicking "Pay", you agree to our terms of service
                                        </p>
                                    </div>
                    @elseif(!$clientSecret && !$error)
                        <div class="text-center py-8">
                            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-4 text-sm text-gray-600">Preparing secure payment...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>