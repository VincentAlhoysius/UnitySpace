<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Leaderboard & Penghargaan Kebajikan') }}
        </h2>
    </x-slot>

    <div class="grid lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Badges catalog -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md">
                <div class="max-w-2xl mb-8">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">Daftar Lencana Kehormatan</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 leading-relaxed">
                        Lencana kehormatan diberikan kepada mahasiswa sebagai apresiasi atas kontribusi riil mereka dalam merawat perdamaian, persaudaraan lintas agama, dan gotong royong kebajikan di kampus.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    @foreach($badges as $badge)
                        @php
                            $isEarned = in_array($badge->id, $earnedBadgeIds);
                        @endphp
                        
                        <div class="relative p-6 rounded-2xl border text-center transition flex flex-col justify-between items-center {{ $isEarned ? 'border-amber-400 bg-amber-50/20 dark:bg-amber-950/10 shadow-md' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 opacity-60' }}">
                            
                            @if($isEarned)
                                <span class="absolute top-3 right-3 text-amber-500 text-sm" title="Telah Diperoleh"><i class="fa-solid fa-circle-check"></i></span>
                            @endif

                            <div class="w-20 h-20 rounded-full bg-white dark:bg-slate-900 flex items-center justify-center text-4xl shadow-md border {{ $isEarned ? 'border-amber-300' : 'border-slate-200 dark:border-slate-800' }}">
                                @if($badge->badge_type === 'volunteer')
                                    <i class="fa-solid fa-hand-holding-heart text-teal-500"></i>
                                @elseif($badge->badge_type === 'contributor')
                                    <i class="fa-solid fa-comments text-indigo-500"></i>
                                @else
                                    <i class="fa-solid fa-medal text-amber-500"></i>
                                @endif
                            </div>

                            <div class="mt-4">
                                <h4 class="font-extrabold text-slate-900 dark:text-white text-base">{{ $badge->name }}</h4>
                                <p class="text-3xs text-slate-450 mt-1 leading-normal font-medium">{{ $badge->description }}</p>
                                <span class="inline-block mt-3 text-4xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">
                                    Syarat: {{ $badge->point_requirement }} Pts
                                </span>
                            </div>

                            <span class="mt-4 inline-block px-3 py-1 rounded-xl text-3xs font-extrabold uppercase {{ $isEarned ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-650' }}">
                                {{ $isEarned ? 'Lencana Diperoleh' : 'Terkunci' }}
                            </span>

                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right 1 Column: Global Top 10 Contributor Leaderboard -->
        <div>
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                <h3 class="font-extrabold text-slate-850 dark:text-white mb-6 flex items-center gap-2 border-b pb-3">
                    <i class="fa-solid fa-trophy text-amber-500 text-lg animate-bounce"></i>Top 10 Contributor Kampus
                </h3>

                <div class="space-y-4">
                    @foreach($leaderboard as $index => $leadUser)
                        @php
                            $isTopThree = $index < 3;
                            $userRowStyle = auth()->check() && $leadUser->id === auth()->id() ? 'border-indigo-400 bg-indigo-50/20' : 'border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-950';
                        @endphp
                        
                        <div class="flex items-center justify-between p-3.5 rounded-2xl border transition {{ $userRowStyle }}">
                            <div class="flex items-center gap-3">
                                @if($index === 0)
                                    <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-400 to-orange-400 text-white font-extrabold flex items-center justify-center shadow-md text-sm">
                                        <i class="fa-solid fa-crown text-xs"></i>
                                    </span>
                                @elseif($index === 1)
                                    <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-slate-300 to-slate-400 text-white font-extrabold flex items-center justify-center shadow-md text-sm">
                                        2
                                    </span>
                                @elseif($index === 2)
                                    <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-600 to-amber-700 text-white font-extrabold flex items-center justify-center shadow-md text-sm">
                                        3
                                    </span>
                                @else
                                    <span class="w-8 h-8 rounded-xl text-slate-450 font-bold flex items-center justify-center text-xs">
                                        {{ $index + 1 }}
                                    </span>
                                @endif

                                <div>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-[110px]">
                                        {{ $leadUser->name }}
                                    </p>
                                    <p class="text-4xs text-slate-450 truncate max-w-[110px]">
                                        {{ $leadUser->faculty }} • {{ $leadUser->religion }}
                                    </p>
                                </div>
                            </div>

                            <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">{{ $leadUser->points }} <span class="text-4xs font-bold text-slate-400">Pts</span></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
