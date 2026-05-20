<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Aksi Nyata & Kegiatan Kebajikan') }}
            </h2>
            
            @auth
                @if(Auth::user()->hasRole(['admin', 'moderator']))
                    <a href="{{ route('events.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-2xl text-sm font-bold shadow-lg shadow-indigo-500/20 transition duration-150">
                        <i class="fa-solid fa-plus mr-1.5"></i>Buat Event Baru
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="space-y-12">
        
        <!-- Search and Category Filters -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
            <form action="{{ route('events.index') }}" method="GET" class="grid sm:grid-cols-12 gap-4 items-center">
                <!-- Keep Category -->
                <input type="hidden" name="category" value="{{ request('category') }}">

                <!-- Search Input -->
                <div class="sm:col-span-8 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event, kerelawanan, atau lokasi..." 
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="sm:col-span-4">
                    <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-indigo-650 dark:hover:bg-indigo-600 text-white rounded-2xl text-sm font-bold shadow-md transition duration-150">
                        Cari Event
                    </button>
                </div>
            </form>

            <!-- Category Filters -->
            <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <a href="{{ route('events.index', array_merge(request()->query(), ['category' => ''])) }}" 
                   class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') == '' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-850' }}">
                    Semua Event
                </a>
                @foreach($categories as $key => $label)
                    <a href="{{ route('events.index', array_merge(request()->query(), ['category' => $key])) }}" 
                       class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') === $key ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-850' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Upcoming Events Grid -->
        <div class="space-y-6">
            <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-hourglass-start text-indigo-500"></i>Event Mendatang
            </h3>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($upcomingEvents as $event)
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-md flex flex-col h-full hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        
                        <!-- Poster Preview with Placeholder if Null -->
                        <div class="h-48 bg-indigo-500 relative flex items-center justify-center text-white">
                            @if($event->poster)
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-6 space-y-2">
                                    <i class="fa-solid fa-image text-4xl opacity-50"></i>
                                    <p class="text-xs font-bold uppercase tracking-widest opacity-70">Poster Event</p>
                                </div>
                            @endif
                            <span class="absolute top-4 right-4 inline-flex px-3 py-1 rounded-lg text-2xs font-extrabold uppercase bg-indigo-950/80 backdrop-blur text-white tracking-wider">
                                {{ $event->category }}
                            </span>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white line-clamp-2 leading-tight">
                                    {{ $event->title }}
                                </h3>
                                <p class="text-slate-500 dark:text-slate-450 text-sm mt-2 line-clamp-3 leading-relaxed font-medium">
                                    {{ strip_tags($event->description) }}
                                </p>
                            </div>

                            <div class="space-y-2 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar text-indigo-500"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }} • {{ $event->time }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-indigo-500"></i>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-650 dark:text-slate-350 font-bold pt-2">
                                    <span>Kuota: {{ $event->activeRegistrations()->count() }}/{{ $event->quota }}</span>
                                    <span class="text-teal-650 dark:text-teal-400 font-extrabold">+{{ $event->points_reward }} Pts</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('events.show', $event->id) }}" class="block w-full text-center py-3 bg-slate-50 hover:bg-indigo-650 hover:text-white dark:bg-slate-950 dark:hover:bg-indigo-600 font-bold text-sm text-indigo-600 dark:text-indigo-400 rounded-2xl transition duration-150">
                                Detail Event
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-800 text-slate-500">
                        Belum ada event mendatang yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Past Events Section -->
        <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-850">
            <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i>Event yang Telah Selesai
            </h3>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($pastEvents as $event)
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-850 overflow-hidden shadow-sm flex flex-col h-full hover:shadow-md opacity-75 hover:opacity-100 transition duration-300">
                        
                        <div class="h-40 bg-slate-200 dark:bg-slate-800 relative flex items-center justify-center text-slate-500">
                            @if($event->poster)
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="w-full h-full object-cover grayscale">
                            @else
                                <div class="text-center p-4">
                                    <i class="fa-solid fa-image text-3xl opacity-30"></i>
                                </div>
                            @endif
                            <span class="absolute top-4 right-4 inline-flex px-3 py-1 rounded-lg text-2xs font-extrabold uppercase bg-slate-900/80 text-white tracking-wider">
                                {{ $event->category }}
                            </span>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 line-clamp-2">
                                    {{ $event->title }}
                                </h3>
                                <p class="text-slate-500 text-xs mt-2 line-clamp-3">
                                    {{ strip_tags($event->description) }}
                                </p>
                            </div>

                            <div class="space-y-1 mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-3xs text-slate-500">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar"></i>
                                    <span>Selesai pada: {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('events.show', $event->id) }}" class="block w-full text-center py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-950 dark:hover:bg-slate-850 font-bold text-xs text-slate-700 dark:text-slate-350 rounded-xl transition">
                                Lihat Dokumentasi
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-slate-500">
                        Belum ada event historis yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
