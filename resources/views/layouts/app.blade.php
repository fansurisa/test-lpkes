<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LPKESolutions') - Platform Pelatihan Kesehatan</title>
    <meta name="description" content="@yield('description', 'Platform informasi pelatihan kesehatan untuk tenaga kesehatan dan non-nakes Indonesia.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 antialiased">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">LPKESolutions</span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('catalog.index') }}" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition-colors">
                        Katalog Pelatihan
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition-colors">
                            Dashboard
                        </a>
                    @endauth
                </div>

                {{-- Auth buttons --}}
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn-outline text-sm px-4 py-2 hidden sm:inline-flex">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm px-4 py-2">
                            Daftar
                        </a>
                    @else
                        {{-- Cart icon --}}
                        @php $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->count(); @endphp
                        <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Keranjang">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-primary-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                                </span>
                            @endif
                        </a>
                        {{-- User menu --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                <img src="{{ auth()->user()->avatar_url }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover ring-2 ring-primary-100">
                                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Str::words(auth()->user()->name, 2, '') }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->isNakes())
                                        <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Tenaga Kesehatan
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                    Dashboard Saya
                                </a>
                                <a href="{{ route('cart.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Keranjang Saya
                                    @if($cartCount > 0)
                                        <span class="ml-auto text-[10px] font-bold bg-primary-100 text-primary-600 px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil Saya
                                </a>
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success') || session('error') || session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="alert-success flex items-start gap-2" x-data x-init="setTimeout(() => $el.remove(), 5000)">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error flex items-start gap-2" x-data x-init="setTimeout(() => $el.remove(), 5000)">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="alert-info flex items-start gap-2" x-data x-init="setTimeout(() => $el.remove(), 5000)">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif
        </div>
    @endif

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold">LPKESolutions</span>
                    </div>
                    <p class="text-sm leading-relaxed">
                        Platform informasi pelatihan kesehatan terpercaya untuk tenaga kesehatan dan masyarakat Indonesia.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Tautan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Katalog Pelatihan</a></li>
                        <li><a href="{{ route('catalog.index') }}?type=event" class="hover:text-white transition-colors">Event</a></li>
                        <li><a href="{{ route('catalog.index') }}?type=ecourse" class="hover:text-white transition-colors">E-Course</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Akun</h4>
                    <ul class="space-y-2 text-sm">
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar</a></li>
                        @else
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                            <li><a href="{{ route('dashboard.profile') }}" class="hover:text-white transition-colors">Profil Saya</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} LPKESolutions. Hak cipta dilindungi undang-undang.</p>
            </div>
        </div>
    </footer>

</body>
</html>
