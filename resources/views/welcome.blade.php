<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>UnitySpace - Membangun Kampus Harmonis Melalui Keberagaman</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
        
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Hero Section -->
        <header class="relative overflow-hidden py-20 lg:py-32 bg-gradient-to-br from-indigo-50 via-white to-teal-50/30 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 transition-colors duration-300">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-indigo-200/20 via-transparent to-transparent dark:from-indigo-900/10 pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid md:grid-cols-12 gap-12 items-center">
                    <div class="md:col-span-7 space-y-6 text-center md:text-left">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50">
                            <i class="fa-solid fa-graduation-cap"></i>Platform Sosial Toleransi Kampus
                        </span>
                        
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight tracking-tight text-slate-900 dark:text-white">
                            Membangun Kampus <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-teal-500 dark:from-indigo-400 dark:to-teal-300">Harmonis</span> Melalui Keberagaman
                        </h1>
                        
                        <p class="text-lg text-slate-600 dark:text-slate-350 max-w-xl mx-auto md:mx-0">
                            UnitySpace adalah wadah inklusif bagi mahasiswa untuk saling berdiskusi, mengikuti aksi kemanusiaan lintas agama, serta melakukan asesmen toleransi demi mewujudkan keadilan sosial (SDG 10 & 16).
                        </p>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-indigo-600/20 transition-all text-center">
                                    Masuk ke Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-indigo-600/20 transition-all text-center">
                                    Join Community <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i>
                                </a>
                            @endauth
                            <a href="{{ route('events.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/80 font-bold rounded-2xl border border-slate-200 dark:border-slate-800 transition text-center">
                                Explore Events
                            </a>
                        </div>
                    </div>
                    
                    <div class="md:col-span-5 relative">
                        <!-- Premium illustration frame -->
                        <div class="relative mx-auto max-w-[380px] md:max-w-none">
                            <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-indigo-500 to-teal-500 opacity-30 blur-lg"></div>
                            <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-2xl transition duration-300">
                                <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
                                    <span class="font-bold text-slate-800 dark:text-white flex items-center gap-1.5"><i class="fa-solid fa-heart text-rose-500 animate-pulse"></i> Duta Harmonis Kampus</span>
                                    <span class="px-2 py-0.5 rounded text-3xs font-extrabold bg-teal-50 text-teal-600 dark:bg-teal-950 dark:text-teal-400 uppercase tracking-wider">Teraktif</span>
                                </div>
                                
                                <div class="space-y-4">
                                    <!-- Fake leaderboard visual list -->
                                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-400 to-orange-400 text-white font-bold flex items-center justify-center shadow-lg">1</div>
                                            <div>
                                                <p class="text-sm font-extrabold">Evelyn Wijaya</p>
                                                <p class="text-3xs text-slate-400">Kedokteran • Buddha</p>
                                            </div>
                                        </div>
                                        <span class="font-extrabold text-indigo-600 dark:text-indigo-400"><i class="fa-solid fa-star text-xs mr-0.5"></i>320 Pts</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-300 to-slate-400 text-white font-bold flex items-center justify-center shadow-lg">2</div>
                                            <div>
                                                <p class="text-sm font-extrabold">Clara Indah</p>
                                                <p class="text-3xs text-slate-400">Hukum • Katolik</p>
                                            </div>
                                        </div>
                                        <span class="font-extrabold text-indigo-600 dark:text-indigo-400"><i class="fa-solid fa-star text-xs mr-0.5"></i>240 Pts</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-600 to-amber-700 text-white font-bold flex items-center justify-center shadow-lg">3</div>
                                            <div>
                                                <p class="text-sm font-extrabold">Budi Mahasiswa</p>
                                                <p class="text-3xs text-slate-400">Ekonomi • Islam</p>
                                            </div>
                                        </div>
                                        <span class="font-extrabold text-indigo-600 dark:text-indigo-400"><i class="fa-solid fa-star text-xs mr-0.5"></i>150 Pts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Statistics Section (Animated Counters via Alpine) -->
        <section class="py-16 bg-white dark:bg-slate-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8 text-center" 
                     x-data="{
                         users: 0,
                         events: 0,
                         discussions: 0,
                         init() {
                             let uTarget = {{ $totalUsers }};
                             let eTarget = {{ $totalEvents }};
                             let dTarget = {{ $totalDiscussions }};
                             
                             let uTimer = setInterval(() => { if(this.users < uTarget) this.users++; else clearInterval(uTimer); }, 50);
                             let eTimer = setInterval(() => { if(this.events < eTarget) this.events++; else clearInterval(eTimer); }, 150);
                             let dTimer = setInterval(() => { if(this.discussions < dTarget) this.discussions++; else clearInterval(dTimer); }, 100);
                         }
                     }">
                    
                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-900 shadow-sm transition">
                        <i class="fa-solid fa-users text-4xl text-indigo-600 dark:text-indigo-400 mb-2"></i>
                        <p class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white" x-text="users">0</p>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-1">Mahasiswa Aktif</p>
                    </div>
                    
                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-900 shadow-sm transition">
                        <i class="fa-solid fa-calendar-days text-4xl text-teal-500 dark:text-teal-400 mb-2"></i>
                        <p class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white" x-text="events">0</p>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-1">Event Kebajikan</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-900 shadow-sm transition col-span-2 md:col-span-1">
                        <i class="fa-solid fa-comments text-4xl text-indigo-500 dark:text-indigo-400 mb-2"></i>
                        <p class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white" x-text="discussions">0</p>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-1">Thread Diskusi</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Events Section -->
        <section class="py-20 bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between mb-12 gap-4 text-center sm:text-left">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white">Kegiatan Terdekat Lintas Agama</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Bergabunglah dalam aksi nyata, dialog toleransi, dan gerakan kerelawanan kampus.</p>
                    </div>
                    <a href="{{ route('events.index') }}" class="px-5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 dark:hover:bg-indigo-900/50 font-bold rounded-xl transition text-sm">
                        Lihat Semua Event <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($featuredEvents as $event)
                        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-150 dark:border-slate-800 overflow-hidden shadow-md flex flex-col h-full hover:shadow-xl hover:-translate-y-1 transition duration-300">
                            <!-- Category Badge -->
                            <div class="p-6 pb-2">
                                <span class="inline-flex px-3 py-1 rounded-lg text-2xs font-extrabold uppercase bg-teal-50 text-teal-600 dark:bg-teal-950/50 dark:text-teal-400 tracking-wider">
                                    {{ $event->category }}
                                </span>
                            </div>

                            <div class="px-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white line-clamp-2 leading-tight">
                                        {{ $event->title }}
                                    </h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 line-clamp-3 leading-relaxed">
                                        {{ strip_tags($event->description) }}
                                    </p>
                                </div>

                                <div class="space-y-2 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-indigo-500"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-location-dot text-indigo-500"></i>
                                        <span class="truncate">{{ $event->location }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-slate-650 dark:text-slate-350 font-bold pt-2">
                                        <span>Quota: {{ $event->activeRegistrations()->count() }}/{{ $event->quota }}</span>
                                        <span class="text-teal-600 dark:text-teal-400 font-extrabold">+{{ $event->points_reward }} Pts</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 pt-4">
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full text-center py-3 bg-slate-50 hover:bg-indigo-650 hover:text-white dark:bg-slate-950 dark:hover:bg-indigo-600 font-bold text-sm text-indigo-600 dark:text-indigo-400 rounded-2xl transition duration-150">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 text-slate-500 dark:text-slate-400">
                            Belum ada event terdekat.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Latest Articles Section -->
        <section class="py-20 bg-white dark:bg-slate-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between mb-12 gap-4 text-center sm:text-left">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white">Edu Center: Pusat Edukasi Keberagaman</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Eksplorasi wawasan teoretis dan praktis tentang inklusi, toleransi, dan SDGs.</p>
                    </div>
                    <a href="{{ route('articles.index') }}" class="px-5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 dark:hover:bg-indigo-900/50 font-bold rounded-xl transition text-sm">
                        Lihat Semua Artikel <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($latestArticles as $article)
                        <div class="bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-slate-900 overflow-hidden shadow-sm flex flex-col h-full hover:shadow-md transition duration-300 p-6">
                            
                            <span class="inline-flex w-fit px-3 py-1 rounded-lg text-2xs font-extrabold uppercase bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 tracking-wider mb-4">
                                {{ $article->category }}
                            </span>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white line-clamp-2 leading-tight">
                                        {{ $article->title }}
                                    </h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-3 line-clamp-3 leading-relaxed">
                                        {{ strip_tags($article->content) }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-200/50 dark:border-slate-800/50">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-850 dark:text-slate-200">{{ $article->user->name }}</p>
                                        <p class="text-3xs text-slate-450">{{ $article->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('articles.show', $article->id) }}" class="block text-center mt-6 py-2.5 font-bold text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition">
                                Baca Selengkapnya <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 text-slate-500 dark:text-slate-400">
                            Belum ada artikel yang diterbitkan.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-20 bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white">Apa Kata Mahasiswa?</h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-2">Dampak nyata UnitySpace dalam merajut toleransi dan persaudaraan lintas agama di lingkungan kampus.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-150 dark:border-slate-800 shadow-md text-left flex flex-col justify-between">
                        <i class="fa-solid fa-quote-left text-teal-400 text-3xl mb-4"></i>
                        <p class="text-slate-650 dark:text-slate-300 text-sm leading-relaxed mb-6">
                            "Sebelumnya, interaksi saya dengan mahasiswa beragama lain sangat terbatas. Melalui program bersih-bersih rumah ibadah dari UnitySpace, saya belajar arti harmoni yang sesungguhnya."
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white font-bold flex items-center justify-center">C</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Clara Indah</h4>
                                <p class="text-3xs text-slate-400">Fakultas Hukum • Katolik</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial 2 -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-150 dark:border-slate-800 shadow-md text-left flex flex-col justify-between">
                        <i class="fa-solid fa-quote-left text-indigo-400 text-3xl mb-4"></i>
                        <p class="text-slate-650 dark:text-slate-300 text-sm leading-relaxed mb-6">
                            "Fitur self-assessment toleransi sangat menarik! Saya bisa melihat sejauh mana pemahaman inklusivitas saya dan mendapatkan insight interpretasi yang logis untuk dikembangkan."
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-500 text-white font-bold flex items-center justify-center">B</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Budi Mahasiswa</h4>
                                <p class="text-3xs text-slate-400">Fakultas Ekonomi • Islam</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial 3 -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-150 dark:border-slate-800 shadow-md text-left flex flex-col justify-between">
                        <i class="fa-solid fa-quote-left text-teal-400 text-3xl mb-4"></i>
                        <p class="text-slate-650 dark:text-slate-300 text-sm leading-relaxed mb-6">
                            "UnitySpace memberikan wadah lapor anonim yang tepercaya. Saat ada candaan SARA di grup maba, tim moderator menindaklanjuti secara bijak tanpa memicu ketegangan baru."
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-550 text-white font-bold flex items-center justify-center">E</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Evelyn Wijaya</h4>
                                <p class="text-3xs text-slate-400">Fakultas Kedokteran • Buddha</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bottom CTA -->
        <section class="py-20 bg-indigo-600 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-stops))] from-indigo-850 via-transparent to-transparent opacity-60"></div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Mari Bersama Ciptakan Kampus Ramah & Toleran!</h2>
                <p class="text-indigo-100 max-w-xl mx-auto">
                    Kecilkan kesenjangan sosial, perluas perdamaian, dan dapatkan poin serta lencana kemanusiaan di UnitySpace.
                </p>
                <div class="pt-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-white text-indigo-600 font-bold hover:bg-indigo-50 rounded-2xl shadow-xl transition-all inline-block">
                            Masuk ke Dashboard Anda
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 font-bold hover:bg-indigo-50 rounded-2xl shadow-xl transition-all inline-block">
                            Buat Akun Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Simple footer wrapper for guest view welcome page -->
        <footer class="bg-slate-950 text-slate-450 py-12 border-t border-slate-900 text-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2 font-extrabold text-xl tracking-wider text-indigo-400">
                    <i class="fa-solid fa-handshake-angle text-teal-400"></i>
                    <span>UnitySpace</span>
                </div>
                <p>&copy; {{ date('Y') }} UnitySpace. SDG 10 & 16 Interfaith Student Platform.</p>
                <div class="flex gap-4">
                    <a href="https://github.com" target="_blank" class="hover:text-white transition"><i class="fa-brands fa-github text-lg"></i></a>
                    <a href="https://instagram.com" target="_blank" class="hover:text-white transition"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="https://linkedin.com" target="_blank" class="hover:text-white transition"><i class="fa-brands fa-linkedin text-lg"></i></a>
                </div>
            </div>
        </footer>
    </body>
</html>
