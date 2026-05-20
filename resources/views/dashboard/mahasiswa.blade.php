<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        
        <!-- Welcome Card & Quick Statistics -->
        <div class="grid lg:grid-cols-3 gap-6">
            
            <!-- Welcome Info -->
            <div class="lg:col-span-2 bg-gradient-to-r from-indigo-600 to-indigo-700 dark:from-indigo-900 dark:to-indigo-950 text-white rounded-3xl p-8 relative overflow-hidden shadow-xl">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-teal-500/20 rounded-full blur-xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <span class="inline-flex px-3 py-1 rounded-lg text-2xs font-extrabold bg-white/20 uppercase tracking-wider mb-2">
                            Selamat Datang kembali
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-black leading-tight">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="text-sm text-indigo-100 mt-2 font-medium">
                            {{ Auth::user()->faculty }} • {{ Auth::user()->department }} 
                            @if(Auth::user()->religion)
                                • Agama: {{ Auth::user()->religion }}
                            @endif
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10 w-full md:w-auto">
                        <i class="fa-solid fa-star text-3xl text-teal-400"></i>
                        <div>
                            <p class="text-3xs uppercase tracking-wider text-indigo-200">Total Poin Anda</p>
                            <p class="text-2xl font-black">{{ Auth::user()->points }} <span class="text-sm font-semibold text-teal-350">Pts</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mini Quick Actions -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md flex flex-col justify-between">
                <h4 class="font-extrabold text-slate-800 dark:text-white mb-4">Aksi Cepat</h4>
                <div class="grid grid-cols-2 gap-3 flex-1">
                    <a href="{{ route('quiz.index') }}" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 transition text-center">
                        <i class="fa-solid fa-brain text-xl mb-1"></i>
                        <span class="text-xs font-bold">Mulai Quiz</span>
                    </a>
                    <a href="{{ route('forums.index') }}" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-teal-50 hover:bg-teal-100 dark:bg-teal-950/30 dark:hover:bg-teal-900/40 text-teal-600 dark:text-teal-400 transition text-center">
                        <i class="fa-solid fa-comments text-xl mb-1"></i>
                        <span class="text-xs font-bold">Forum Baru</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/50 dark:hover:bg-slate-850 text-slate-700 dark:text-slate-200 transition text-center">
                        <i class="fa-solid fa-calendar-days text-xl mb-1"></i>
                        <span class="text-xs font-bold">Cari Event</span>
                    </a>
                    <a href="{{ route('reports.create') }}" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-450 transition text-center">
                        <i class="fa-solid fa-shield-halved text-xl mb-1"></i>
                        <span class="text-xs font-bold">Lapor SARA</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Participation Statistics Section -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-bookmark"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase">Event Diikuti</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $registeredEventsCount }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase">Kehadiran Event</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $attendedEventsCount }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-comment-dots"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase">Post Forum</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $forumPostsCount }}</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase">Komentar Forum</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $commentsCount }}</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Main Activities logs -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Upcoming Registered Events -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-indigo-500"></i>Event Terbaru yang Bisa Diikuti
                        </h4>
                        <a href="{{ route('events.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua</a>
                    </div>
                    
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 space-y-4">
                        @forelse($recentEvents as $event)
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4 first:pt-0">
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded text-3xs font-extrabold bg-teal-50 text-teal-600 dark:bg-teal-950/50 dark:text-teal-400 uppercase tracking-wide mb-1">
                                        {{ $event->category }}
                                    </span>
                                    <h5 class="font-bold text-slate-900 dark:text-white hover:text-indigo-500 transition line-clamp-1">
                                        <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                                    </h5>
                                    <p class="text-xs text-slate-450 mt-1">
                                        <i class="fa-regular fa-clock mr-1 text-slate-400"></i>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }} • {{ $event->time }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 w-full sm:w-auto">
                                    <span class="text-xs font-extrabold text-teal-600 dark:text-teal-400">+{{ $event->points_reward }} Pts</span>
                                    <a href="{{ route('events.show', $event->id) }}" class="px-4 py-2 bg-slate-50 hover:bg-indigo-600 hover:text-white dark:bg-slate-950 dark:hover:bg-indigo-600 text-indigo-650 dark:text-indigo-400 text-xs font-bold rounded-xl transition w-full sm:w-auto text-center">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 dark:text-slate-400 text-sm py-4">Belum ada event terbaru.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Forum discussions -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-comments text-indigo-500"></i>Forum Diskusi Terbaru
                        </h4>
                        <a href="{{ route('forums.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Semua Forum</a>
                    </div>
                    
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 space-y-4">
                        @forelse($recentForums as $forum)
                            <div class="pt-4 first:pt-0">
                                <div class="flex items-center gap-2 justify-between">
                                    <span class="inline-block px-2 py-0.5 rounded text-3xs font-extrabold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 uppercase tracking-wide">
                                        {{ $forum->category }}
                                    </span>
                                    <span class="text-xs text-slate-450">
                                        <i class="fa-regular fa-heart mr-0.5"></i>{{ $forum->likes_count }} Upvotes
                                    </span>
                                </div>
                                <h5 class="font-bold text-slate-900 dark:text-white mt-1 hover:text-indigo-500 transition">
                                    <a href="{{ route('forums.show', $forum->id) }}">{{ $forum->title }}</a>
                                </h5>
                                <p class="text-xs text-slate-450 mt-1 line-clamp-1">
                                    Diajukan oleh: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $forum->user->name }}</span> • {{ $forum->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 dark:text-slate-400 text-sm py-4">Belum ada thread diskusi.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Gamification Badges list -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-trophy text-amber-500"></i>Lencana & Pencapaian Anda
                    </h4>
                    
                    <div class="grid sm:grid-cols-3 gap-6">
                        @foreach($allBadges as $badge)
                            @php
                                $isEarned = in_array($badge->id, $userBadgeIds);
                            @endphp
                            <div class="relative p-5 rounded-2xl border text-center transition flex flex-col justify-between items-center {{ $isEarned ? 'border-amber-400 bg-amber-50/20 dark:bg-amber-950/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 opacity-60' }}">
                                @if($isEarned)
                                    <span class="absolute top-2 right-2 text-amber-500 text-sm"><i class="fa-solid fa-circle-check"></i></span>
                                @endif
                                <div class="w-16 h-16 rounded-full bg-white dark:bg-slate-900 flex items-center justify-center text-3xl shadow-md border {{ $isEarned ? 'border-amber-300' : 'border-slate-200 dark:border-slate-800' }}">
                                    @if($badge->badge_type === 'volunteer')
                                        <i class="fa-solid fa-hand-holding-heart text-teal-500"></i>
                                    @elseif($badge->badge_type === 'contributor')
                                        <i class="fa-solid fa-comments text-indigo-500"></i>
                                    @else
                                        <i class="fa-solid fa-medal text-amber-500 animate-bounce"></i>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <h5 class="font-bold text-sm text-slate-900 dark:text-white">{{ $badge->name }}</h5>
                                    <p class="text-3xs text-slate-450 mt-1 leading-normal">{{ $badge->description }}</p>
                                </div>
                                <span class="mt-3 inline-block px-2 py-0.5 rounded text-3xs font-bold uppercase {{ $isEarned ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-650' }}">
                                    {{ $isEarned ? 'Unlocked' : 'Locked' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Leaderboard & Quiz history -->
            <div class="space-y-8">
                
                <!-- Leaderboard top 5 -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-award text-amber-500 text-lg"></i>Top Contributor Kampus
                    </h4>
                    
                    <div class="space-y-4">
                        @foreach($leaderboard as $index => $leadUser)
                            <div class="flex items-center justify-between p-3 rounded-xl border {{ $leadUser->id === Auth::id() ? 'border-indigo-400 bg-indigo-50/20' : 'border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-950' }}">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $index === 0 ? 'bg-amber-400 text-white' : ($index === 1 ? 'bg-slate-350 text-white' : ($index === 2 ? 'bg-amber-700 text-white' : 'text-slate-450')) }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-[120px]">{{ $leadUser->name }}</p>
                                        <p class="text-4xs text-slate-450 truncate max-w-[120px]">{{ $leadUser->faculty }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">{{ $leadUser->points }} Pts</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Self-Assessment History -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i>Riwayat Self-Assessment
                    </h4>
                    
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                        @forelse($userResults as $res)
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-xl flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-850 dark:text-slate-200">
                                        Skor: <span class="text-indigo-600 dark:text-indigo-400">{{ $res->score }}/50</span>
                                    </p>
                                    <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-3xs font-extrabold uppercase {{ $res->category === 'tinggi' ? 'bg-emerald-100 text-emerald-700' : ($res->category === 'sedang' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ $res->category }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-4xs text-slate-450 block">{{ $res->created_at->format('d/m/y') }}</span>
                                    <a href="{{ route('quiz.result', $res->id) }}" class="text-3xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Detail</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 dark:text-slate-400 text-xs py-4 leading-relaxed">
                                Belum ada riwayat. <br>
                                <a href="{{ route('quiz.index') }}" class="font-bold text-indigo-600 dark:text-indigo-400">Mulai Asesmen Pertama</a>
                            </p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
