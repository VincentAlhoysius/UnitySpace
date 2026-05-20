<nav x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-2xl tracking-wider text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-handshake-angle text-teal-500 dark:text-teal-400"></i>
                        <span>Unity<span class="text-teal-600 dark:text-teal-400">Space</span></span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex space-x-1">
                    @auth
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>Dashboard</span>
                        </a>
                    @endauth
                    
                    <a href="{{ route('forums.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('forums.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                        <i class="fa-solid fa-comments"></i>
                        <span>Forum</span>
                    </a>

                    <a href="{{ route('events.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('events.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Event</span>
                    </a>

                    <a href="{{ route('articles.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('articles.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Edu Center</span>
                    </a>

                    @auth
                        <a href="{{ route('quiz.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('quiz.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                            <i class="fa-solid fa-brain"></i>
                            <span>Quiz Toleransi</span>
                        </a>
                    @endauth

                    <a href="{{ route('badges.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('badges.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                        <i class="fa-solid fa-trophy"></i>
                        <span>Leaderboard</span>
                    </a>

                    <a href="{{ route('reports.create') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('reports.create') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                        <i class="fa-solid fa-shield-halved text-rose-500"></i>
                        <span class="text-rose-600 dark:text-rose-450 font-bold">Lapor</span>
                    </a>

                    @auth
                        @if(Auth::user()->hasRole(['admin', 'moderator']))
                            <a href="{{ route('reports.index') }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('reports.index') ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-350 dark:hover:bg-slate-800/50' }}">
                                <i class="fa-solid fa-user-shield"></i>
                                <span>Kelola Laporan</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-4">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2.5 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition duration-150">
                    <i class="fa-solid fa-sun text-lg dark:hidden"></i>
                    <i class="fa-solid fa-moon text-lg hidden dark:block"></i>
                </button>

                @auth
                    <!-- Notifications Button -->
                    <a href="{{ route('notifications.index') }}" class="relative p-2.5 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition duration-150">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @php
                            $unreadCount = Auth::user()->unreadNotifications->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-2xs font-extrabold leading-none text-white bg-rose-500 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Profile Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-2 p-1.5 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 text-left">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500 text-white font-bold flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block pr-2">
                                <p class="text-xs font-semibold leading-tight text-slate-800 dark:text-white max-w-[120px] truncate">
                                    {{ Auth::user()->name }}
                                </p>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-3xs font-extrabold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 uppercase tracking-wide">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs hidden sm:block"></i>
                        </button>

                        <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl py-1 z-50 text-slate-700 dark:text-slate-350">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-xs font-medium text-slate-400">Poin Anda</p>
                                <p class="text-lg font-bold text-teal-600 dark:text-teal-400">
                                    <i class="fa-solid fa-star text-sm mr-1"></i>{{ Auth::user()->points }} Pts
                                </p>
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-slate-800 transition">
                                <i class="fa-solid fa-user-gear mr-2 text-indigo-500"></i>Ubah Profil
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-slate-800 transition">
                                    <i class="fa-solid fa-right-from-bracket mr-2 text-rose-500"></i>Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guests Login/Register Links -->
                    <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/25 transition">Daftar</a>
                @endauth

                <!-- Hamburger -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2.5 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition">
                    <i class="fa-solid fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-xmark text-xl" x-show="mobileMenuOpen"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenuOpen" class="md:hidden border-t border-slate-100 dark:border-slate-800 px-4 pt-2 pb-4 space-y-1.5 bg-white dark:bg-slate-900 shadow-xl transition-all duration-300">
        @auth
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-semibold hover:bg-slate-50 dark:hover:bg-slate-850">
                <i class="fa-solid fa-gauge-high mr-2 text-indigo-500"></i>Dashboard
            </a>
        @endauth
        <a href="{{ route('forums.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold hover:bg-slate-50 dark:hover:bg-slate-850">
            <i class="fa-solid fa-comments mr-2 text-indigo-500"></i>Forum Diskusi
        </a>
        <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold hover:bg-slate-50 dark:hover:bg-slate-850">
            <i class="fa-solid fa-calendar-days mr-2 text-indigo-500"></i>Event Kampus
        </a>
        <a href="{{ route('articles.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold hover:bg-slate-50 dark:hover:bg-slate-850">
            <i class="fa-solid fa-book-open mr-2 text-indigo-500"></i>Edu Center
        </a>
        @auth
            <a href="{{ route('quiz.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold hover:bg-slate-50 dark:hover:bg-slate-850">
                <i class="fa-solid fa-brain mr-2 text-indigo-500"></i>Quiz Toleransi
            </a>
        @endauth
        <a href="{{ route('badges.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold hover:bg-slate-50 dark:hover:bg-slate-850">
            <i class="fa-solid fa-trophy mr-2 text-indigo-500"></i>Leaderboard
        </a>
        <a href="{{ route('reports.create') }}" class="block px-3 py-2 rounded-lg text-base font-bold text-rose-600 hover:bg-rose-50/50">
            <i class="fa-solid fa-shield-halved mr-2 text-rose-500"></i>Lapor Isu SARA
        </a>

        @auth
            @if(Auth::user()->hasRole(['admin', 'moderator']))
                <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-lg text-base font-bold text-teal-600 hover:bg-teal-50/50">
                    <i class="fa-solid fa-user-shield mr-2 text-teal-500"></i>Kelola Laporan
                </a>
            @endif
            
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 mt-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-indigo-600 text-white font-bold flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs font-bold bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 rounded-lg">
                        Keluar
                    </button>
                </form>
            </div>
        @else
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex gap-2">
                <a href="{{ route('login') }}" class="flex-1 text-center py-2 text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:bg-slate-50 dark:hover:bg-slate-850 rounded-lg">Masuk</a>
                <a href="{{ route('register') }}" class="flex-1 text-center py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold shadow-lg">Daftar</a>
            </div>
        @endauth
    </div>
</nav>
