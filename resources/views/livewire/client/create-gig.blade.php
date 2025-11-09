<div>
    <!-- Trigger Button -->
    <button wire:click="openModal" 
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition">
        <i class="fas fa-plus mr-2"></i> Post a New Gig
    </button>

    <!-- Modal -->
    @if($modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
                
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl">
                    <h3 class="text-2xl font-bold text-white">Post a New Gig</h3>
                </div>

                <div class="p-8">
                    <form wire:submit.prevent="createGig">
                        
                        <!-- Title -->
                        <div class="mb-6">
                            <label class="block text-lg font-semibold mb-2">Title</label>
                            <input type="text" 
                                   wire:model.live="title"
                                   class="w-full px-4 py-3 border-2 rounded-xl focus:border-green-500"
                                   placeholder="e.g. Build a Laravel SaaS">
                            @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            
                            <div class="mt-2 text-sm text-gray-600">
                                URL: {{ url('/gig') }}/<span class="font-bold text-green-600">{{ $slugPreview ?: 'your-slug' }}</span>
                            </div>
                        </div>

                        <!-- Budget Type -->
                        <div class="mb-6">
                            <label class="block text-lg font-semibold mb-3">Budget Type</label>
                            <div class="flex gap-8">
                                <label class="flex items-center">
                                    <input type="radio" 
                                           wire:model.live="budgetType" 
                                           value="range" 
                                           class="mr-3 text-green-600"
                                           name="budgetType">
                                    <span>Range</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" 
                                           wire:model.live="budgetType" 
                                           value="fixed" 
                                           class="mr-3 text-green-600"
                                           name="budgetType">
                                    <span>Fixed Price</span>
                                </label>
                            </div>
                        </div>

                        <!-- Budget Fields -->
                        @if($budgetType === 'range')
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label>Min Budget</label>
                                    <input type="number" wire:model.live="budget_min" class="w-full px-4 py-3 border-2 rounded-xl">
                                    @error('budget_min') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label>Max Budget</label>
                                    <input type="number" wire:model.live="budget_max" class="w-full px-4 py-3 border-2 rounded-xl">
                                    @error('budget_max') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @else
                            <div class="mb-6">
                                <label>Fixed Budget</label>
                                <input type="number" wire:model.live="budget_fixed" class="w-full px-4 py-3 border-2 rounded-xl">
                                @error('budget_fixed') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="mb-6">
                            <label class="block text-lg font-semibold mb-2">Description</label>
                            <textarea wire:model.defer="description" 
                                      rows="8"
                                      class="w-full px-4 py-3 border-2 rounded-xl"
                                      placeholder="Describe your project..."></textarea>
                            <div class="text-right text-sm text-gray-500">
                                {{ strlen($description) }}/5000
                            </div>
                            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-4">
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="px-8 py-3 bg-gray-300 hover:bg-gray-400 rounded-xl font-semibold">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold">
                                Post Gig
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>