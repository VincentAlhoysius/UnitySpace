<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-indigo-600 transition">
                <i class="fa-solid fa-arrow-left"></i> Hub Edukasi
            </a>
            
            <div class="flex gap-2">
                @auth
                    @if(Auth::id() === $article->user_id || Auth::user()->hasRole('admin'))
                        <a href="{{ route('articles.edit', $article->id) }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl text-3xs font-bold text-slate-700 dark:text-slate-200 transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                        </a>
                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 rounded-xl text-3xs font-bold text-rose-600 transition">
                                <i class="fa-solid fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="grid lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Full Article Story -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Article Canvas Card -->
            <article class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-md p-6 md:p-8">
                
                <!-- Category and Read Time Row -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-4 mb-6">
                    <span class="inline-flex px-3.5 py-1.5 rounded-xl text-2xs font-extrabold uppercase bg-indigo-50 text-indigo-650 dark:bg-indigo-950/50 dark:text-indigo-400 tracking-wider">
                        {{ $article->category }}
                    </span>
                    
                    @php
                        // reading time meter (assume 200 words per minute)
                        $wordCount = str_word_count(strip_tags($article->content));
                        $minutes = ceil($wordCount / 200);
                        if ($minutes <= 0) $minutes = 1;
                    @endphp
                    <span class="text-3xs font-bold text-slate-400 uppercase tracking-wide">
                        <i class="fa-regular fa-clock mr-1"></i>{{ $minutes }} Menit Baca
                    </span>
                </div>

                <!-- Title & Meta -->
                <h1 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white leading-tight">
                    {{ $article->title }}
                </h1>

                <!-- Author details -->
                <div class="flex items-center gap-3 mt-6 pb-6 border-b border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-black">
                        {{ strtoupper(substr($article->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
                            Diterbitkan oleh: <span class="text-indigo-650 dark:text-indigo-400">{{ $article->user->name }}</span>
                        </p>
                        <p class="text-3xs text-slate-450 mt-0.5">
                            Spesialis: {{ $article->user->faculty }} • {{ $article->created_at->translatedFormat('d F Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                <!-- Image Banner if Exists -->
                @if($article->thumbnail)
                    <div class="my-6 max-h-[380px] w-full overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Article Body Content -->
                <div class="text-slate-700 dark:text-slate-300 text-sm md:text-base leading-relaxed mt-6 font-medium space-y-4 prose dark:prose-invert max-w-none">
                    {!! $article->content !!}
                </div>
            </article>

        </div>

        <!-- Right 1 Column: Related Articles Sidebar -->
        <div class="space-y-8">
            
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                <h3 class="font-extrabold text-slate-850 dark:text-white mb-6 flex items-center gap-2 border-b pb-3">
                    <i class="fa-solid fa-graduation-cap text-indigo-500"></i>Artikel Terkait ({{ ucfirst($article->category) }})
                </h3>

                <div class="space-y-6">
                    @forelse($relatedArticles as $rel)
                        <div class="space-y-2">
                            <span class="inline-block px-2 py-0.5 rounded text-4xs font-bold bg-indigo-50 text-indigo-600">
                                {{ $rel->category }}
                            </span>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-2 hover:text-indigo-500 transition leading-snug">
                                <a href="{{ route('articles.show', $rel->id) }}">{{ $rel->title }}</a>
                            </h4>
                            <p class="text-4xs text-slate-450">{{ $rel->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 text-xs py-4">Belum ada artikel serupa.</p>
                    @endforelse
                </div>
            </div>

            <!-- Share Box -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md text-center">
                <h4 class="font-extrabold text-slate-850 dark:text-white mb-4">Sebarkan Wawasan Toleransi</h4>
                <div class="flex items-center justify-center gap-3">
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link disalin ke clipboard!')" class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-indigo-50 dark:bg-slate-950 text-slate-500 hover:text-indigo-600 flex items-center justify-center text-sm border transition">
                        <i class="fa-solid fa-link"></i>
                    </button>
                    <a href="https://twitter.com" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-sky-50 dark:bg-slate-950 text-slate-500 hover:text-sky-500 flex items-center justify-center text-sm border transition">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-blue-50 dark:bg-slate-950 text-slate-500 hover:text-blue-600 flex items-center justify-center text-sm border transition">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
