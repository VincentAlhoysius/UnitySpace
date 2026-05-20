<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Edu Center: Hub Edukasi Keberagaman') }}
            </h2>
            
            @auth
                @if(Auth::user()->hasRole(['admin', 'moderator']))
                    <a href="{{ route('articles.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-2xl text-sm font-bold shadow-lg shadow-indigo-500/20 transition duration-150">
                        <i class="fa-solid fa-plus mr-1.5"></i>Tulis Artikel Baru
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="space-y-12">
        
        <!-- Search and Filters Panel -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
            <form action="{{ route('articles.index') }}" method="GET" class="grid sm:grid-cols-12 gap-4 items-center">
                <!-- Category persistence -->
                <input type="hidden" name="category" value="{{ request('category') }}">

                <!-- Search Input -->
                <div class="sm:col-span-8 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel, topik hak asasi, toleransi..." 
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="sm:col-span-4">
                    <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-indigo-650 dark:hover:bg-indigo-600 text-white rounded-2xl text-sm font-bold shadow-md transition duration-150">
                        Cari Artikel
                    </button>
                </div>
            </form>

            <!-- Filters -->
            <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <a href="{{ route('articles.index', array_merge(request()->query(), ['category' => ''])) }}" 
                   class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') == '' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-850' }}">
                    Semua Artikel
                </a>
                @foreach($categories as $key => $label)
                    <a href="{{ route('articles.index', array_merge(request()->query(), ['category' => $key])) }}" 
                       class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') === $key ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-850' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Articles Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-md flex flex-col h-full hover:shadow-xl hover:-translate-y-0.5 transition duration-300">
                    
                    <!-- Thumbnail with placeholder -->
                    <div class="h-44 bg-indigo-50 relative flex items-center justify-center text-slate-400">
                        @if($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-4 opacity-50">
                                <i class="fa-solid fa-book text-4xl mb-1"></i>
                                <p class="text-4xs font-bold uppercase tracking-wider">Artikel Edukasi</p>
                            </div>
                        @endif
                        <span class="absolute top-4 right-4 inline-flex px-3 py-1 rounded-lg text-2xs font-extrabold uppercase bg-slate-900/80 backdrop-blur text-white tracking-wider">
                            {{ $article->category }}
                        </span>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white line-clamp-2 leading-tight">
                                <a href="{{ route('articles.show', $article->id) }}" class="hover:text-indigo-500 transition">{{ $article->title }}</a>
                            </h3>
                            <p class="text-slate-500 dark:text-slate-450 text-sm mt-3 line-clamp-3 leading-relaxed font-medium">
                                {{ strip_tags($article->content) }}
                            </p>
                        </div>

                        <!-- Author card row -->
                        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($article->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-850 dark:text-slate-200 leading-tight">{{ $article->user->name }}</p>
                                <p class="text-4xs text-slate-450 mt-0.5">{{ $article->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <a href="{{ route('articles.show', $article->id) }}" class="block w-full text-center py-2.5 bg-slate-50 hover:bg-indigo-650 hover:text-white dark:bg-slate-950 dark:hover:bg-indigo-600 font-bold text-xs text-indigo-600 dark:text-indigo-400 rounded-xl transition duration-150">
                            Baca Artikel
                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-3 bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-800 text-slate-550 dark:text-slate-400">
                    <i class="fa-regular fa-folder-open text-5xl mb-4 text-slate-350"></i>
                    <p class="text-lg font-bold">Artikel tidak ditemukan.</p>
                    <p class="text-xs text-slate-450 mt-1">Coba cari dengan kata kunci lain atau sesuaikan kategori.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div>
            {{ $articles->links() }}
        </div>

    </div>
</x-app-layout>
