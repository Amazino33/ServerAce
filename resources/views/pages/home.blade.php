<x-app-layout>
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-gray-50 via-white to-green-50 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-green-400 to-blue-500 rounded-full opacity-20 blur-3xl animate-pulse"></div>
            <div class="absolute top-1/2 -left-40 w-80 h-80 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute -bottom-40 right-1/4 w-96 h-96 bg-gradient-to-br from-green-500 to-teal-500 rounded-full opacity-20 blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left space-y-8 z-10">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 py-2 bg-white rounded-full shadow-lg border border-green-100">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></span>
                        <span class="text-sm font-semibold text-gray-700">Trusted by 10,000+ Businesses</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-tight">
                        <span class="block text-gray-900">Find Expert</span>
                        <span class="block bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mt-2">
                            Server Admins
                        </span>
                        <span class="block text-gray-900 mt-2">In Minutes</span>
                    </h1>

                    <!-- Subheading -->
                    <p class="text-xl text-gray-600 max-w-2xl leading-relaxed">
                        Connect with skilled professionals for Linux, Windows, cPanel, DDoS protection, and server optimization. Get your projects done fast and right.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('gigs.index') }}" 
                           class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-600 to-blue-600 text-white font-bold rounded-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            Browse Gigs
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('post-gig') }}" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-bold rounded-xl border-2 border-gray-200 hover:border-green-500 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                            Post a Gig
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-200">
                        <div class="text-center lg:text-left">
                            <div class="text-3xl font-bold text-gray-900">1,000+</div>
                            <div class="text-sm text-gray-600 mt-1">Active Gigs</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl font-bold text-gray-900">5,000+</div>
                            <div class="text-sm text-gray-600 mt-1">Professionals</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl font-bold text-gray-900">98%</div>
                            <div class="text-sm text-gray-600 mt-1">Success Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Right Image/Illustration -->
                <div class="relative z-10 hidden lg:block">
                    <div class="relative">
                        <!-- Floating Card 1 -->
                        <div class="absolute -top-8 -left-8 bg-white rounded-2xl shadow-2xl p-4 animate-float" style="animation-delay: 0s;">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-server text-white text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Linux Admin</div>
                                    <div class="text-sm font-bold text-gray-900">$150/hr</div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Card 2 -->
                        <div class="absolute -bottom-8 -right-8 bg-white rounded-2xl shadow-2xl p-4 animate-float" style="animation-delay: 1s;">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Security Expert</div>
                                    <div class="text-sm font-bold text-gray-900">$200/hr</div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Image -->
                        {{-- <div class="relative bg-gradient-to-br from-green-100 to-blue-100 rounded-3xl p-8 shadow-2xl">
                            <img src="{{ asset('storage/images/dev/hero-img.svg') }}" 
                                 alt="Server Admin Illustration" 
                                 class="w-full h-auto relative z-10">
                            
                            <!-- Decorative Elements -->
                            <div class="absolute top-1/4 -left-4 w-24 h-24 bg-green-400 rounded-full opacity-30 blur-2xl"></div>
                            <div class="absolute bottom-1/4 -right-4 w-24 h-24 bg-blue-400 rounded-full opacity-30 blur-2xl"></div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Why Choose <span class="bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">ServerAce</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Everything you need to find and hire the best server administrators
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 hover:border-green-500">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-blue-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-bolt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Fast Delivery</h3>
                    <p class="text-gray-600">Get your server issues resolved in hours, not days. Our experts work quickly and efficiently.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 hover:border-green-500">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure & Reliable</h3>
                    <p class="text-gray-600">All work is protected with secure escrow payments and verified professional credentials.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 hover:border-green-500">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Top Talent</h3>
                    <p class="text-gray-600">Access pre-vetted server administrators with proven track records and excellent reviews.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 hover:border-green-500">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Fair Pricing</h3>
                    <p class="text-gray-600">Transparent pricing with no hidden fees. Pay only for the work you need.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 hover:border-green-500">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-headset text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
                    <p class="text-gray-600">Round-the-clock customer support to help you every step of the way.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 hover:border-green-500">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Management</h3>
                    <p class="text-gray-600">Simple dashboard to track projects, communicate, and manage payments seamlessly.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-green-600 to-blue-600 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Get Started?
            </h2>
            <p class="text-xl text-white opacity-90 mb-10 max-w-2xl mx-auto">
                Join thousands of businesses finding expert server administrators for their projects
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-green-600 font-bold rounded-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 shadow-lg">
                    <i class="fas fa-rocket mr-2"></i>
                    Create Free Account
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
                
                <a href="{{ route('gigs.index') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-transparent text-white font-bold rounded-xl border-2 border-white hover:bg-white hover:text-green-600 transition-all duration-300">
                    <i class="fas fa-eye mr-2"></i>
                    View Available Gigs
                </a>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-12 flex flex-wrap justify-center items-center gap-8 text-white opacity-75">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>No credit card required</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Free to join</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Cancel anytime</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Add floating animation --}}
    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</x-app-layout>