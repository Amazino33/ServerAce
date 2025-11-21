<x-app-layout>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                    <p class="text-gray-600 mt-1">Update your profile information and settings</p>
                </div>
                <a href="{{ route('profile.show', auth()->user()->id) }}" 
                   class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-900">Profile Completion</h3>
                <span class="text-sm font-bold text-green-600">{{ auth()->user()->profile_completion }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500" 
                     style="width: {{ auth()->user()->profile_completion }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                @if(auth()->user()->profile_completion < 100)
                    Complete your profile to increase visibility and trust
                @else
                    <i class="fas fa-check-circle text-green-600"></i> Your profile is complete!
                @endif
            </p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Column - Avatar & Visibility -->
                <div class="space-y-6">
                    
                    <!-- Avatar Upload -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-camera text-green-600 mr-2"></i>
                            Profile Picture
                        </h3>
                        
                        <div class="text-center">
                            <div class="relative inline-block">
                                <img id="avatar-preview" 
                                     src="{{ auth()->user()->avatar_url }}" 
                                     alt="{{ auth()->user()->name }}"
                                     class="w-32 h-32 rounded-full border-4 border-gray-200 object-cover mx-auto mb-4">
                                <label for="avatar" class="absolute bottom-2 right-2 bg-green-600 hover:bg-green-700 text-white rounded-full p-2 cursor-pointer transition">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" 
                                           id="avatar" 
                                           name="avatar" 
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewAvatar(event)">
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">JPG, PNG or GIF (max. 2MB)</p>
                            @error('avatar')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Visibility Settings -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-eye text-green-600 mr-2"></i>
                            Visibility
                        </h3>
                        
                        <div class="space-y-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="profile_public" 
                                       value="1"
                                       {{ auth()->user()->profile_public ? 'checked' : '' }}
                                       class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <div class="ml-3">
                                    <span class="font-semibold text-gray-900">Public Profile</span>
                                    <p class="text-sm text-gray-500">Allow others to view your profile</p>
                                </div>
                            </label>
                            
                            @if(auth()->user()->role->value === 'freelancer')
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="available_for_work" 
                                           value="1"
                                           {{ auth()->user()->available_for_work ? 'checked' : '' }}
                                           class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <div class="ml-3">
                                        <span class="font-semibold text-gray-900">Available for Work</span>
                                        <p class="text-sm text-gray-500">Show you're accepting new projects</p>
                                    </div>
                                </label>
                            @endif
                        </div>
                    </div>

                    @if(auth()->user()->role->value === 'freelancer')
                        <!-- Payment Status -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-credit-card text-green-600 mr-2"></i>
                                Payment Status
                            </h3>
                            
                            @if(auth()->user()->stripe_onboarded)
                                <div class="text-center py-4">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-check-circle text-3xl text-green-600"></i>
                                    </div>
                                    <p class="font-semibold text-gray-900">Payment Setup Complete</p>
                                    <p class="text-sm text-gray-600 mt-1">You can receive payments</p>
                                    {{-- <a href="{{ route('freelancer.stripe.account-dashboard') }}" 
                                       class="mt-3 inline-block text-green-600 hover:text-green-700 font-semibold text-sm">
                                        View Stripe Dashboard →
                                    </a> --}}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-exclamation-triangle text-3xl text-yellow-600"></i>
                                    </div>
                                    <p class="font-semibold text-gray-900">Payment Setup Required</p>
                                    <p class="text-sm text-gray-600 mt-1">Complete setup to receive payments</p>
                                    <a href="{{ route('freelancer.stripe.onboarding') }}" 
                                       class="mt-3 inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition text-sm">
                                        Setup Payments
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- Right Column - Profile Information -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            Basic Information
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="username" 
                                       value="{{ old('username', auth()->user()->username) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('username')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       value="{{ auth()->user()->email }}"
                                       disabled
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                                <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Location
                                </label>
                                <div class="relative">
                                    <i class="fas fa-map-marker-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" 
                                           name="location" 
                                           value="{{ old('location', auth()->user()->location) }}"
                                           placeholder="e.g., New York, USA"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>
                                @error('location')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone
                                </label>
                                <div class="relative">
                                    <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="tel" 
                                           name="phone" 
                                           value="{{ old('phone', auth()->user()->phone) }}"
                                           placeholder="+1 (555) 123-4567"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Bio
                            </label>
                            <textarea name="bio" 
                                      rows="4"
                                      placeholder="Tell us about yourself..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('bio', auth()->user()->bio) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Brief description for your profile (max 1000 characters)</p>
                            @error('bio')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Role-Specific Information -->
                    @if(auth()->user()->role->value === 'freelancer')
                        <!-- Freelancer Info -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-briefcase text-green-600 mr-2"></i>
                                Professional Information
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Professional Title
                                    </label>
                                    <input type="text" 
                                           name="title" 
                                           value="{{ old('title', auth()->user()->title) }}"
                                           placeholder="e.g., Full Stack Developer, UI/UX Designer"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('title')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Hourly Rate (USD)
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-dollar-sign absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        <input type="number" 
                                               name="hourly_rate" 
                                               value="{{ old('hourly_rate', auth()->user()->hourly_rate) }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="50.00"
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    @error('hourly_rate')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Experience Level
                                    </label>
                                    <select name="experience_level"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Select level</option>
                                        <option value="beginner" {{ old('experience_level', auth()->user()->experience_level) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ old('experience_level', auth()->user()->experience_level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="expert" {{ old('experience_level', auth()->user()->experience_level) === 'expert' ? 'selected' : '' }}>Expert</option>
                                    </select>
                                    @error('experience_level')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Skills
                                    </label>
                                    <div id="skills-container" class="space-y-2">
                                        @if(auth()->user()->skills && count(auth()->user()->skills) > 0)
                                            @foreach(auth()->user()->skills as $index => $skill)
                                                <div class="flex gap-2 skill-input">
                                                    <input type="text" 
                                                           name="skills[]" 
                                                           value="{{ $skill }}"
                                                           placeholder="e.g., Laravel, React, UI Design"
                                                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    <button type="button" 
                                                            onclick="removeSkill(this)"
                                                            class="px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="flex gap-2 skill-input">
                                                <input type="text" 
                                                       name="skills[]" 
                                                       placeholder="e.g., Laravel, React, UI Design"
                                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                <button type="button" 
                                                        onclick="removeSkill(this)"
                                                        class="px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" 
                                            onclick="addSkill()"
                                            class="mt-3 px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition font-semibold text-sm">
                                        <i class="fas fa-plus mr-2"></i>Add Skill
                                    </button>
                                    @error('skills')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Portfolio Description
                                    </label>
                                    <textarea name="portfolio_description" 
                                              rows="5"
                                              placeholder="Describe your experience, expertise, and what makes you unique..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('portfolio_description', auth()->user()->portfolio_description) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">This will be displayed on your profile (max 2000 characters)</p>
                                    @error('portfolio_description')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Portfolio Images -->
<div class="bg-white rounded-xl shadow-lg p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-900 mb-6">
        Portfolio (up to 12 images)
    </h3>

    <!-- Upload Zone -->
    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-green-500 transition cursor-pointer"
         onclick="document.getElementById('portfolio-input').click()">
        <input type="file" 
               id="portfolio-input" 
               name="portfolio[]" 
               multiple 
               accept="image/*" 
               class="hidden">
        <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-4"></i>
        <p class="text-lg font-semibold text-gray-700">Drop images here or click to upload</p>
        <p class="text-sm text-gray-500">JPG, PNG, WebP • Max 5MB each</p>
    </div>

    <!-- Preview Grid -->
    <div id="portfolio-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        @foreach(auth()->user()->getMedia('portfolio') as $media)
            <div class="relative group portfolio-item" data-id="{{ $media->id }}">
                <img src="{{ $media->getUrl('thumb') }}" 
                     class="w-full h-48 object-cover rounded-lg shadow-lg hover:shadow-xl transition">
                <div class="absolute inset-0 bg-black bg-opacity-70 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <form action="{{ route('profile.portfolio.delete', $media) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white p-4 rounded-full transition">
                            <i class="fas fa-trash text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
document.getElementById('portfolio-input').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('portfolio-preview');

    if (files.length === 0) return;

    const formData = new FormData();
    for (let file of files) {
        if (file.size > 5 * 1024 * 1024) {
            alert('Too big: ' + file.name);
            continue;
        }
        formData.append('portfolio[]', file);
    }

    fetch('{{ route('profile.portfolio.upload') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        location.reload();
    })
    .catch(err => {
        alert('Upload failed. Please try again.');
        console.error(err);
    });
});
</script>
                            </div>
                        </div>
                    @else
                        <!-- Client/Company Info -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-building text-green-600 mr-2"></i>
                                Company Information
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Company Name
                                    </label>
                                    <input type="text" 
                                           name="company_name" 
                                           value="{{ old('company_name', auth()->user()->company_name) }}"
                                           placeholder="Your Company Inc."
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('company_name')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Industry
                                    </label>
                                    <input type="text" 
                                           name="industry" 
                                           value="{{ old('industry', auth()->user()->industry) }}"
                                           placeholder="e.g., Technology, Healthcare, Finance"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('industry')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Company Size
                                    </label>
                                    <select name="company_size"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Select size</option>
                                        <option value="1-10" {{ old('company_size', auth()->user()->company_size) === '1-10' ? 'selected' : '' }}>1-10 employees</option>
                                        <option value="11-50" {{ old('company_size', auth()->user()->company_size) === '11-50' ? 'selected' : '' }}>11-50 employees</option>
                                        <option value="51-200" {{ old('company_size', auth()->user()->company_size) === '51-200' ? 'selected' : '' }}>51-200 employees</option>
                                        <option value="201-500" {{ old('company_size', auth()->user()->company_size) === '201-500' ? 'selected' : '' }}>201-500 employees</option>
                                        <option value="500+" {{ old('company_size', auth()->user()->company_size) === '500+' ? 'selected' : '' }}>500+ employees</option>
                                    </select>
                                    @error('company_size')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Social Links -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-link text-green-600 mr-2"></i>
                            Links & Social Media
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Website
                                </label>
                                <div class="relative">
                                    <i class="fas fa-globe absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="url" 
                                           name="website" 
                                           value="{{ old('website', auth()->user()->website) }}"
                                           placeholder="https://yourwebsite.com"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>
                                @error('website')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    LinkedIn
                                </label>
                                <div class="relative">
                                    <i class="fab fa-linkedin absolute left-3 top-1/2 -translate-y-1/2 text-blue-600"></i>
                                    <input type="url" 
                                           name="linkedin_url" 
                                           value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}"
                                           placeholder="https://linkedin.com/in/yourprofile"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>
                                @error('linkedin_url')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    GitHub
                                </label>
                                <div class="relative">
                                    <i class="fab fa-github absolute left-3 top-1/2 -translate-y-1/2 text-gray-700"></i>
                                    <input type="url" 
                                           name="github_url" 
                                           value="{{ old('github_url', auth()->user()->github_url) }}"
                                           placeholder="https://github.com/yourusername"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>
                                @error('github_url')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Twitter
                                </label>
                                <div class="relative">
                                    <i class="fab fa-twitter absolute left-3 top-1/2 -translate-y-1/2 text-blue-400"></i>
                                    <input type="url" 
                                           name="twitter_url" 
                                           value="{{ old('twitter_url', auth()->user()->twitter_url) }}"
                                           placeholder="https://twitter.com/yourusername"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>
                                @error('twitter_url')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex gap-4">
                        <button type="submit" 
                                class="flex-1 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-bold text-lg transition shadow-lg">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                        <a href="{{ route('profile.show', auth()->user()->id) }}" 
                           class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition">
                            Cancel
                        </a>
                    </div>

                </div>
            </div>
        </form>

        <!-- Additional Sections -->
        <div class="grid lg:grid-cols-2 gap-8 mt-8">
            
            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-lock text-green-600 mr-2"></i>
                    Change Password
                </h3>
                <p class="text-gray-600 mb-4">Update your password to keep your account secure</p>
                <a href="{{ route('password.request') }}" 
                   class="inline-block px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition">
                    <i class="fas fa-key mr-2"></i>Change Password
                </a>
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Danger Zone
                </h3>
                <p class="text-gray-600 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                <button type="button"
                        onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone!')) { document.getElementById('delete-account-form').submit(); }"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                    <i class="fas fa-trash mr-2"></i>Delete Account
                </button>
                <form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

        </div>
    </div>
</div>

<script>
// Avatar preview
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

// Skills management
function addSkill() {
    const container = document.getElementById('skills-container');
    const skillInput = document.createElement('div');
    skillInput.className = 'flex gap-2 skill-input';
    skillInput.innerHTML = `
        <input type="text" 
               name="skills[]" 
               placeholder="e.g., Laravel, React, UI Design"
               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        <button type="button" 
                onclick="removeSkill(this)"
                class="px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(skillInput);
}

function removeSkill(button) {
    const container = document.getElementById('skills-container');
    const skillInputs = container.querySelectorAll('.skill-input');
    
    // Keep at least one input
    if (skillInputs.length > 1) {
        button.closest('.skill-input').remove();
    } else {
        button.closest('.skill-input').querySelector('input').value = '';
    }
}

function previewPortfolioImages(event) {
    const files = event.target.files;
    const preview = document.getElementById('portfolio-preview');

    Array.from(files).forEach(file => {
        if (file.size > 5 * 1024 * 1024) {
            alert('Image too large: ' + file.name);
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group portfolio-item';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-48 object-cover rounded-lg shadow">
                <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center">
                    <button type="button" onclick="this.closest('.portfolio-item').remove()" 
                            class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
}

function removePortfolioImage(mediaId, button) {
    if (!confirm('Delete this image?')) return;

    fetch(`/profile/portfolio/${mediaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => {
        button.closest('.portfolio-item').remove();
    });
}

document.getElementById('portfolio-upload').addEventListener('change', previewPortfolioImages);

</script>
</x-app-layout>