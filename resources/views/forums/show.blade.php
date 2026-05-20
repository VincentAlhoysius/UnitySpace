<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('forums.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-indigo-600 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Forum
            </a>
            
            <div class="flex gap-2">
                @auth
                    @if(Auth::id() === $forum->user_id || Auth::user()->hasRole(['admin', 'moderator']))
                        <button @click="$dispatch('open-modal', 'edit-thread-modal')" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl text-3xs font-bold text-slate-700 dark:text-slate-200 transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                        </button>
                        <form action="{{ route('forums.destroy', $forum->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus diskusi ini?')">
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

    <div class="space-y-8">
        
        <!-- Thread Main Card -->
        <article class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md">
            <!-- Author Meta Header -->
            <div class="flex justify-between items-start gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-black text-lg">
                        {{ strtoupper(substr($forum->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-slate-900 dark:text-white">{{ $forum->user->name }}</span>
                            <span class="inline-flex px-1.5 py-0.5 rounded text-4xs font-extrabold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 uppercase tracking-wide">
                                {{ $forum->user->role }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-450 mt-0.5">
                            {{ $forum->user->faculty }} • {{ $forum->user->department }} • {{ $forum->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <span class="px-3.5 py-1.5 rounded-xl text-2xs font-extrabold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 uppercase tracking-wider">
                    {{ $forum->category }}
                </span>
            </div>

            <!-- Title & Body Content -->
            <div class="mt-6">
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-tight">
                    {{ $forum->title }}
                </h1>
                <div class="text-slate-650 dark:text-slate-300 text-sm md:text-base leading-relaxed mt-4 font-medium space-y-4 whitespace-pre-line">
                    {{ $forum->content }}
                </div>
            </div>

            <!-- Bottom Upvote / Actions Footer -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-100 dark:border-slate-800/80">
                <div class="flex items-center gap-2">
                    <form action="{{ route('forums.like', $forum->id) }}" method="POST">
                        @csrf
                        @php
                            $userLiked = auth()->check() && $forum->likes->contains('user_id', auth()->id());
                        @endphp
                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-2xl text-xs font-bold transition {{ $userLiked ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 border border-indigo-200' : 'bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 text-slate-500' }}">
                            <i class="fa-solid fa-circle-arrow-up text-sm"></i>
                            <span>{{ $forum->likes_count }} Upvotes</span>
                        </button>
                    </form>
                </div>

                <!-- Report thread -->
                <form action="{{ route('forums.report', $forum->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Laporkan diskusi ini?')" class="text-4xs font-bold text-rose-500 hover:underline">
                        <i class="fa-solid fa-flag mr-1"></i>Laporkan Isu SARA
                    </button>
                </form>
            </div>
        </article>

        <!-- Comments Section -->
        <section id="comments-section" class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md space-y-8">
            <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-regular fa-comment text-indigo-500"></i>Diskusi & Komentar ({{ $forum->comments->count() }})
            </h3>

            <!-- Comment Input Box (For Logged in Users) -->
            @auth
                <form action="{{ route('forums.comments.store', $forum->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea name="content" required rows="3" placeholder="Tuliskan gagasan, tanggapan toleran, atau klarifikasi sehat Anda di sini..." 
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-indigo-500/20">
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl text-center border text-xs">
                    Silakan <a href="{{ route('login') }}" class="font-bold text-indigo-650 hover:underline">Login</a> untuk memberikan kontribusi komentar.
                </div>
            @endauth

            <!-- Comments List Tree -->
            <div class="space-y-6 pt-4 border-t border-slate-100 dark:border-slate-850">
                @forelse($forum->rootComments as $comment)
                    
                    <!-- Root Comment Card -->
                    <div class="space-y-4 border-l-2 border-slate-100 dark:border-slate-800 pl-4 sm:pl-6" x-data="{ showReplyForm: false }">
                        
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-950 text-slate-700 dark:text-slate-300 font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $comment->user->name }}</span>
                                        <span class="inline-flex px-1 rounded text-4xs font-extrabold bg-slate-50 text-slate-500 uppercase">
                                            {{ $comment->user->role }}
                                        </span>
                                    </div>
                                    <p class="text-4xs text-slate-450">{{ $comment->user->department }} • {{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @auth
                                @if(Auth::id() === $comment->user_id || Auth::user()->hasRole(['admin', 'moderator']))
                                    <form action="{{ route('forums.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-3xs font-bold transition">Hapus</button>
                                    </form>
                                @endif
                            @endauth
                        </div>

                        <!-- Comment content -->
                        <p class="text-sm text-slate-700 dark:text-slate-350 leading-relaxed font-medium">
                            {{ $comment->content }}
                        </p>

                        <!-- Reply action toggle -->
                        @auth
                            <div class="flex items-center gap-4 text-3xs font-bold">
                                <button @click="showReplyForm = !showReplyForm" class="text-slate-450 hover:text-indigo-600 flex items-center gap-1">
                                    <i class="fa-solid fa-reply"></i> Balas
                                </button>
                            </div>

                            <!-- Inline Reply Form -->
                            <form x-show="showReplyForm" action="{{ route('forums.comments.store', $forum->id) }}" method="POST" class="space-y-3 pt-2" style="display: none;">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <textarea name="content" required rows="2" placeholder="Tuliskan balasan komentar..." 
                                          class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-xl py-2 px-3 text-xs focus:ring-1 focus:ring-indigo-500"></textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="showReplyForm = false" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-850 dark:hover:bg-slate-800 rounded-lg text-4xs font-bold transition">Batal</button>
                                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-lg text-4xs font-bold shadow-sm transition">Kirim</button>
                                </div>
                            </form>
                        @endauth

                        <!-- Recursive Sub-Replies Loop -->
                        @if($comment->replies->count() > 0)
                            <div class="space-y-4 mt-4 pl-4 border-l border-indigo-100 dark:border-slate-850/80 bg-indigo-50/5 dark:bg-slate-900/50 p-3 rounded-2xl">
                                @foreach($comment->replies as $reply)
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded bg-slate-100 dark:bg-slate-950 text-slate-650 font-bold flex items-center justify-center text-4xs">
                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-3xs font-extrabold text-slate-850 dark:text-slate-200">
                                                        {{ $reply->user->name }} 
                                                        <span class="text-4xs text-slate-400 font-normal">membalas</span>
                                                    </p>
                                                    <p class="text-4xs text-slate-450">{{ $reply->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>

                                            @auth
                                                @if(Auth::id() === $reply->user_id || Auth::user()->hasRole(['admin', 'moderator']))
                                                    <form action="{{ route('forums.comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Hapus balasan?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-4xs font-bold transition">Hapus</button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                        <p class="text-xs text-slate-700 dark:text-slate-350 leading-relaxed pl-8 font-medium">
                                            {{ $reply->content }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                @empty
                    <p class="text-center text-slate-550 dark:text-slate-400 text-sm py-6">Belum ada komentar. Jadilah yang pertama memberikan gagasan positif!</p>
                @endforelse
            </div>
        </section>

        <!-- Edit Thread Modal (Breeze compatible modal) -->
        <x-modal name="edit-thread-modal" focusable>
            <form action="{{ route('forums.update', $forum->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-3">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">
                        Ubah Diskusi Anda
                    </h3>
                    <button type="button" @click="$dispatch('close')" class="text-slate-400 hover:text-slate-500">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Topic Category -->
                <div>
                    <label for="edit_category" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Kategori Topik</label>
                    <select id="edit_category" name="category" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                        <option value="Toleransi" {{ $forum->category === 'Toleransi' ? 'selected' : '' }}>Toleransi</option>
                        <option value="Keberagaman" {{ $forum->category === 'Keberagaman' ? 'selected' : '' }}>Keberagaman</option>
                        <option value="Pengalaman Kampus" {{ $forum->category === 'Pengalaman Kampus' ? 'selected' : '' }}>Pengalaman Kampus</option>
                        <option value="Diskusi Sosial" {{ $forum->category === 'Diskusi Sosial' ? 'selected' : '' }}>Diskusi Sosial</option>
                        <option value="Event Kampus" {{ $forum->category === 'Event Kampus' ? 'selected' : '' }}>Event Kampus</option>
                    </select>
                </div>

                <!-- Title -->
                <div>
                    <label for="edit_title" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Judul Diskusi</label>
                    <input type="text" id="edit_title" name="title" required value="{{ $forum->title }}" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                </div>

                <!-- Content -->
                <div>
                    <label for="edit_content" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Isi Diskusi</label>
                    <textarea id="edit_content" name="content" rows="6" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">{{ $forum->content }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-850">
                    <button type="button" @click="$dispatch('close')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-bold rounded-2xl text-xs transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-indigo-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-modal>

    </div>
</x-app-layout>
