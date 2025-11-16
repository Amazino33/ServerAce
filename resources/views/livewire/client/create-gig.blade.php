<div>
    <!-- Trigger Button -->
    <button wire:click="openModal"
        class="hidden group inline-flex items-center gap-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-300">
        <i class="fas fa-plus-circle text-xl"></i>
        <span>Post a New Gig</span>
        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
    </button>

    <!-- Modal -->
    @if($modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4"
             x-data
             x-init="$el.focus()"
             @keydown.escape.window="$wire.closeModal()">
            
            <!-- Modal Container -->
            <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden transform transition-all duration-300"
                 @click.away="$wire.closeModal()">

                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-blue-600 px-8 py-6 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold text-white mb-1">Post a New Gig</h3>
                            <p class="text-green-100">Find the perfect expert for your project</p>
                        </div>
                        <button wire:click="closeModal" 
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white transition-all duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="overflow-y-auto max-h-[calc(90vh-140px)] px-8 py-8">
                    <form wire:submit.prevent="createGig" class="space-y-8">

                        <!-- Images Section -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border-2 border-dashed border-gray-300 hover:border-green-500 transition-colors">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-images text-white"></i>
                                </div>
                                <div>
                                    <label class="text-lg font-bold text-gray-900">Project Images</label>
                                    <p class="text-sm text-gray-600">Upload 1-3 images (max 2MB each)</p>
                                </div>
                            </div>

                            <label for="image-input"
                                class="group inline-flex items-center gap-3 bg-white hover:bg-gradient-to-r hover:from-green-600 hover:to-blue-600 border-2 border-green-600 text-green-600 hover:text-white rounded-xl transition-all py-3 px-6 cursor-pointer shadow-md hover:shadow-lg font-semibold">
                                <i class="fas fa-cloud-upload-alt text-xl"></i>
                                <span>Choose Images</span>
                            </label>
                            
                            <input type="file" wire:model="photos" accept="image/*" class="hidden" id="image-input">
                            
                            @error('photos') 
                                <div class="mt-3 flex items-center gap-2 text-red-600 text-sm bg-red-50 px-4 py-2 rounded-lg">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                            @error('photos.*') 
                                <div class="mt-3 flex items-center gap-2 text-red-600 text-sm bg-red-50 px-4 py-2 rounded-lg">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                            <!-- Loading indicator -->
                            <div wire:loading wire:target="photos" class="mt-3 flex items-center gap-2 text-blue-600 text-sm bg-blue-50 px-4 py-2 rounded-lg">
                                <i class="fas fa-spinner fa-spin"></i>
                                <span>Uploading images...</span>
                            </div>

                            <!-- Image Previews -->
                            @if(!empty($imagePreviews))
                                <div class="mt-6 grid grid-cols-3 gap-4">
                                    @foreach ($imagePreviews as $index => $url)
                                        <div class="relative group">
                                            <img src="{{ $url }}" 
                                                 class="w-full h-40 object-cover rounded-xl border-2 border-gray-200 shadow-md group-hover:scale-105 transition-transform duration-300">
                                            <button type="button" wire:click="removeImage({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold shadow-lg transform hover:scale-110 transition-all duration-200">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-md">
                                                Image {{ $index + 1 }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <script>
                                document.addEventListener('livewire:initialized', () => {
                                    @this.on('clear-files', () => {
                                        document.getElementById('image-input').value = '';
                                    });
                                });
                            </script>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-3">
                                <i class="fas fa-heading text-green-600"></i>
                                Gig Title
                            </label>
                            <input type="text" wire:model.live="title"
                                class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none transition-all text-lg"
                                placeholder="e.g., Expert Linux Server Setup & Optimization">
                            @error('title') 
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                            <div class="mt-3 flex items-center gap-2 text-sm text-gray-600 bg-gray-50 px-4 py-3 rounded-lg">
                                <i class="fas fa-link text-green-600"></i>
                                <span class="font-medium">URL Preview:</span>
                                <span class="text-gray-500">{{ url('/gig') }}/</span>
                                <span class="font-bold text-green-600">{{ $slugPreview ?: 'your-slug-here' }}</span>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-3">
                                <i class="fas fa-folder text-green-600"></i>
                                Category
                            </label>

                            @php
                                $menuCat = Cache::remember('menu_categories', now()->addHours(6), function () {
                                    return App\Models\Category::where('in_menu', true)
                                        ->orderBy('menu_order')
                                        ->orderBy('name')
                                        ->get();
                                });
                            @endphp

                            <select wire:model.live="category_id"
                                class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none transition-all text-lg appearance-none bg-white cursor-pointer"
                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3E%3Cpath stroke=\'%236B7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3E%3C/svg%3E'); background-position: right 1rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; padding-right: 3rem;">
                                <option value="0">Select a category...</option>
                                @foreach ($menuCat as $cat)
                                    <option value="{{ $cat->id }}">{{ __($cat->name) }}</option>
                                @endforeach
                            </select>
                            @error('category_id') 
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Budget Type -->
                        <div>
                            <label class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                                <i class="fas fa-dollar-sign text-green-600"></i>
                                Budget Type
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-green-500 hover:shadow-md {{ $budgetType === 'range' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white' }}">
                                    <input type="radio" wire:model.live="budgetType" value="range"
                                        class="mr-4 text-green-600 w-5 h-5" name="budgetType">
                                    <div>
                                        <span class="font-semibold text-gray-900 block">Budget Range</span>
                                        <span class="text-sm text-gray-600">Set minimum and maximum</span>
                                    </div>
                                    @if($budgetType === 'range')
                                        <i class="fas fa-check-circle text-green-600 text-xl ml-auto"></i>
                                    @endif
                                </label>
                                <label class="relative flex items-center p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-green-500 hover:shadow-md {{ $budgetType === 'fixed' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white' }}">
                                    <input type="radio" wire:model.live="budgetType" value="fixed"
                                        class="mr-4 text-green-600 w-5 h-5" name="budgetType">
                                    <div>
                                        <span class="font-semibold text-gray-900 block">Fixed Price</span>
                                        <span class="text-sm text-gray-600">Set exact amount</span>
                                    </div>
                                    @if($budgetType === 'fixed')
                                        <i class="fas fa-check-circle text-green-600 text-xl ml-auto"></i>
                                    @endif
                                </label>
                            </div>
                        </div>

                        <!-- Budget Fields -->
                        @if($budgetType === 'range')
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="flex items-center gap-2 font-semibold text-gray-900 mb-3">
                                        <i class="fas fa-arrow-down text-green-600"></i>
                                        Minimum Budget ($)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-lg">$</span>
                                        <input type="number" wire:model.live="budget_min"
                                            class="w-full pl-10 pr-5 py-4 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none transition-all text-lg"
                                            placeholder="1000">
                                    </div>
                                    @error('budget_min') 
                                        <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="flex items-center gap-2 font-semibold text-gray-900 mb-3">
                                        <i class="fas fa-arrow-up text-green-600"></i>
                                        Maximum Budget ($)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-lg">$</span>
                                        <input type="number" wire:model.live="budget_max"
                                            class="w-full pl-10 pr-5 py-4 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none transition-all text-lg"
                                            placeholder="5000">
                                    </div>
                                    @error('budget_max') 
                                        <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="flex items-center gap-2 font-semibold text-gray-900 mb-3">
                                    <i class="fas fa-dollar-sign text-green-600"></i>
                                    Fixed Budget ($)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-lg">$</span>
                                    <input type="number" wire:model.live="budget_fixed"
                                        class="w-full pl-10 pr-5 py-4 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none transition-all text-lg"
                                        placeholder="3000">
                                </div>
                                @error('budget_fixed') 
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <!-- Description -->
                        <div>
                            <label class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-3">
                                <i class="fas fa-align-left text-green-600"></i>
                                Project Description
                            </label>
                            <textarea wire:model.defer="description" rows="10" 
                                class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none transition-all text-base resize-none"
                                placeholder="Describe your project requirements, deliverables, timeline, and any specific skills needed..."></textarea>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm text-gray-500">Be as detailed as possible to attract the right professionals</span>
                                <span class="text-sm font-medium {{ strlen($description) > 4500 ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ strlen($description) }}/5000
                                </span>
                            </div>
                            @error('description') 
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                    </form>
                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex justify-between items-center sticky bottom-0">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-green-600 mr-1"></i>
                        All fields are required to post your gig
                    </div>
                    <div class="flex gap-4">
                        <button type="button" wire:click="closeModal"
                            class="px-8 py-3 bg-white hover:bg-gray-100 border-2 border-gray-300 rounded-xl font-semibold transition-all duration-200 text-gray-700">
                            Cancel
                        </button>
                        <button type="submit" wire:click="createGig"
                            class="group inline-flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-rocket"></i>
                            <span>Post Gig</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>