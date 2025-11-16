<div>
    <!-- Apply Button -->
    @if ($this->canApply)
        <button wire:click="openModal"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-3 rounded-xl shadow-lg transition">
            <i class="fas fa-paper-plane mr-2"></i>Apply for this Gig
        </button>
    @elseif ($this->userApplication)
        {{-- User has already applied --}}
        <div class="w-full bg-blue-100 border-2 border-blue-500 text-blue-700 py-4 px-8 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Application submitted</strong>
                    <p class="text-sm mt-1">
                        Status:
                        <span class="font-bold capitalize">{{ $this->userApplication->status }}</span>
                    </p>
                </div>
                @if ($this->userApplication->isPending())
                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm text-center">
                        Pending Reveiw
                    </span>
                @elseif ($this->userApplication->isAccepted())
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm text-center">
                        Accepted
                    </span>
                @else
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm text-center">
                        Rejected
                    </span>
                @endif 
            </div>
        </div>

    @elseif (!auth()->check())
        <a href="{{ route('login') }}"
           class="block w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition">
            <i class="fas fa-sign-in-alt mr-2"></i> Login to Apply
        </a>
    @elseif ($gig->client_id === auth()->id())
        <div class="w-full bg-gray-100 border-2 border-gray-300 text-gray-600 py-4 px-8 rounded-xl text-center">
            <i class="fas fa-info-circle mr-2"></i> This is your gig
        </div>
    @else
        <div class="w-full bg-gray-100 border-2 border-gray-300 text-gray-600 py-4 px-8 rounded-xl text-center">
            <i class="fas fa-lock mr-2"></i> Application closed
        </div>
    @endif

    {{-- Application Modal --}}
    @if ($modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl">
                    <h3 class="text-2xl font-bold text-white">Apply For: {{ $gig->title }}</h3>
                </div>

                {{-- Modal Body --}}
                <div class="p-8">
                    <form wire:submit.prevent="submit">

                        {{-- Gig Budget Info --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <p class="text-sm text-gray-700">
                                <strong>Client's Budget:</strong>
                                @if ($gig->budget_fixed)
                                    ${{ number_format($gig->budget_fixed, 2) }} (Fixed)
                                @else
                                    ${{ number_format($gig->budget_min, 2) }} - {{ number_format($gig->budget_max, 2) }}
                                @endif
                            </p>
                        </div>

                        {{-- Proposed Price --}}
                        <div class="mb-6">
                            <label class="block text-lg font-semibold mb-2">
                                Your Proposed Price ($)
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   wire.model="proposed_price"
                                   step="0.01"
                                   min="5"
                                   class="w-full py-4 px-3 border-2 rounded-xl focus:border-green-500 focus:outline-none"
                                   placeholder="e.g. 500.00">
                            @error('proposed_price')
                                <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                            @enderror
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-info-circle"></i>
                                Be competitive but fair. Consider the scope and your expertise.
                            </p>
                        </div>

                        {{-- Cover Letter --}}
                        <div class="mb-6">
                            <label class="block text-lg font-semibold mb-2">
                                Cover Letter
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="cover_letter"
                                      rows="8"
                                      class="w-full py-4 px-3 border-2 rounded-xl focus:border-green-500 focus:outline-none"
                                      placeholder="Tell the client why you are the perfect fit for this project...&#10;&#10;- Your relevant experience&#10;- How you will approach this project&#10;- Why you're interested."></textarea>

                            {{-- Character Counter --}}
                            <div class="flex justify-between text-sm text-gray-500 mt-2">
                                <span>
                                    @error('cover_letter')
                                        <span class="text-red-600">{{ $message }}</span>
                                    @else
                                        Minimum 50 characters
                                    @enderror
                                </span>
                                <span>{{ strlen($cover_letter) }}/1000</span>
                            </div>
                        </div>

                        {{-- Tip Box --}}
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                            <p class="font-semibold text-gray-800 mb-2">
                                <i class="fas fa-lightbulb text-yellow-500"></i>Tips for great application:
                            </p>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                                <li>Be specific about your relevant experience</li>
                                <li>Show you've read and understood the requirements</li>
                                <li>Explain your approach to the project</li>
                                <li>Be professional but personable</li>
                            </ul>
                        </div>

                        {{-- Action Button --}}
                        <div class="flex justify-end gap-4">
                            <button type="button"
                                    wire:click="closeModal"
                                    class="px-8 py-3 bg-gray-300 hover:bg-gray-400 rounded-xl font-semibold transition">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold transition">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Application
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
