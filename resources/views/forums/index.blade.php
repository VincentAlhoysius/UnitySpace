<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Forum Diskusi Kebangsaan & Toleransi') }}
            </h2>
            
            @auth
                <button @click="$dispatch('open-modal', 'create-thread-modal')" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-2xl text-sm font-bold shadow-lg shadow-indigo-600/20 transition duration-150">
                    <i class="fa-solid fa-plus mr-1.5"></i>Buat Diskusi Baru
                </button>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-2xl text-sm font-bold shadow-lg transition duration-150">
                    Login untuk Berdiskusi
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="space-y-8" x-data="{ activeCategory: '{{ request('category') ?? '' }}' }">
        
        <!-- Search and Filter Form Panel -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
            <form action="{{ route('forums.index') }}" method="GET" class="grid sm:grid-cols-12 gap-4 items-center">
                <!-- Keep existing category -->
                <input type="hidden" name="category" :value="activeCategory">

                <!-- Search Input -->
                <div class="sm:col-span-6 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari topik diskusi atau kata kunci..." 
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Sort Dropdown -->
                <div class="sm:col-span-4">
                    <select name="sort" onchange="this.form.submit()" class="w-full py-3 bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Urutan Terbaru</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Populer (Upvotes Terbanyak)</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2">
                    <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-indigo-650 dark:hover:bg-indigo-600 text-white rounded-2xl text-sm font-bold shadow-md transition duration-150">
                        Cari
                    </button>
                </div>
            </form>

            <!-- Category Filter Chips -->
            <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <a href="{{ route('forums.index', array_merge(request()->query(), ['category' => ''])) }}" 
                   class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') == '' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-850' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('forums.index', array_merge(request()->query(), ['category' => $category])) }}" 
                       class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') === $category ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-850' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Discussions Thread List -->
        <div class="space-y-6">
            @forelse($forums as $forum)
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md hover:shadow-xl hover:-translate-y-0.5 transition duration-300">
                    
                    <div class="flex justify-between items-start gap-4">
                        <!-- Left: Author Info & Details -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-black">
                                {{ strtoupper(substr($forum->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $forum->user->name }}</span>
                                    <span class="inline-flex px-1.5 py-0.5 rounded text-4xs font-extrabold bg-slate-50 text-slate-600 dark:bg-slate-950 dark:text-slate-400 uppercase tracking-wide">
                                        {{ $forum->user->role }}
                                    </span>
                                </div>
                                <p class="text-4xs text-slate-450 mt-0.5">
                                    {{ $forum->user->faculty }} • {{ $forum->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <!-- Right: Category Tag -->
                        <span class="px-3 py-1 rounded-xl text-2xs font-extrabold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 uppercase tracking-wider">
                            {{ $forum->category }}
                        </span>
                    </div>

                    <!-- Title & Content Preview -->
                    <div class="mt-4">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white hover:text-indigo-500 transition leading-tight">
                            <a href="{{ route('forums.show', $forum->id) }}">{{ $forum->title }}</a>
                        </h3>
                        <p class="text-slate-500 dark:text-slate-450 text-sm mt-2 line-clamp-3 leading-relaxed font-medium">
                            {{ strip_tags($forum->content) }}
                        </p>
                    </div>

                    <!-- Footer Action Controls (Upvote, Comment Counters, Flag) -->
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                        <div class="flex items-center gap-2">
                            <!-- Like/Upvote form button -->
                            <form action="{{ route('forums.like', $forum->id) }}" method="POST">
                                @csrf
                                @php
                                    $userLiked = auth()->check() && $forum->likes->contains('user_id', auth()->id());
                                @endphp
                                <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $userLiked ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900/50' : 'bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-850 text-slate-500' }}">
                                    <i class="fa-solid fa-circle-arrow-up"></i>
                                    <span>{{ $forum->likes_count }} Upvotes</span>
                                </button>
                            </form>

                            <!-- Comment link -->
                            <a href="{{ route('forums.show', $forum->id) }}#comments-section" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-950 dark:hover:bg-slate-850 text-slate-500 text-xs font-bold transition">
                                <i class="fa-regular fa-comment"></i>
                                <span>{{ $forum->comments->count() }} Komentar</span>
                            </a>
                        </div>

                        <!-- Report thread button -->
                        <form action="{{ route('forums.report', $forum->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Laporkan diskusi ini karena mengandung candaan SARA, fitnah, atau konten ofensif?')" class="text-4xs font-bold text-rose-500 hover:underline">
                                <i class="fa-solid fa-flag mr-1"></i>Laporkan Isu
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-800 shadow-md text-slate-550 dark:text-slate-400">
                    <i class="fa-solid fa-comments text-5xl text-slate-300 dark:text-slate-700 mb-4"></i>
                    <p class="text-lg font-bold">Diskusi tidak ditemukan.</p>
                    <p class="text-xs text-slate-450 mt-1">Coba sesuaikan kata kunci pencarian atau kategori filter Anda.</p>
                </div>
            @endforelse

            <!-- Pagination Links -->
            <div class="pt-4">
                {{ $forums->links() }}
            </div>
        </div>

        <!-- Alpine.js Modals Create Thread (Standard Laravel Breeze Modal) -->
        <x-modal name="create-thread-modal" focusable>
            <form action="{{ route('forums.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-3">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">
                        Buat Diskusi Kebangsaan Baru
                    </h3>
                    <button type="button" @click="$dispatch('close')" class="text-slate-400 hover:text-slate-500">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Topic Category -->
                <div>
                    <label for="category" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Kategori Topik</label>
                    <select id="category" name="category" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                        <option value="">Pilih Kategori</option>
                        <option value="Toleransi">Toleransi</option>
                        <option value="Keberagaman">Keberagaman</option>
                        <option value="Pengalaman Kampus">Pengalaman Kampus</option>
                        <option value="Diskusi Sosial">Diskusi Sosial</option>
                        <option value="Event Kampus">Event Kampus</option>
                    </select>
                </div>

                <!-- Discussion Title -->
                <div>
                    <label for="title" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Judul Diskusi</label>
                    <input type="text" id="title" name="title" required placeholder="Tuliskan judul yang menarik dan mencerminkan toleransi..." 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                </div>

                <!-- Discussion Content -->
                <div>
                    <label for="content" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Isi Diskusi</label>
                    <textarea id="content" name="content" rows="6" required placeholder="Tuliskan latar belakang gagasan, argumen sehat, atau pertanyaan edukatif Anda di sini..." 
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-850">
                    <button type="button" @click="$dispatch('close')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-bold rounded-2xl text-xs transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-indigo-500/20">
                        Terbitkan Thread
                    </button>
                </div>
            </form>
        </x-modal>

    </div>
</x-app-layout>
