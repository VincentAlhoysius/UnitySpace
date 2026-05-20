<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Ubah Artikel Edukasi') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md">
            
            <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Judul Artikel</label>
                    <input type="text" id="title" name="title" required value="{{ old('title', $article->title) }}" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Kategori Edukasi</label>
                    <select id="category" name="category" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                        <option value="toleransi" {{ $article->category === 'toleransi' ? 'selected' : '' }}>Toleransi</option>
                        <option value="keberagaman" {{ $article->category === 'keberagaman' ? 'selected' : '' }}>Keberagaman</option>
                        <option value="anti diskriminasi" {{ $article->category === 'anti diskriminasi' ? 'selected' : '' }}>Anti Diskriminasi</option>
                        <option value="SDGs" {{ $article->category === 'SDGs' ? 'selected' : '' }}>SDGs & Inklusi</option>
                        <option value="hak asasi manusia" {{ $article->category === 'hak asasi manusia' ? 'selected' : '' }}>Hak Asasi Manusia</option>
                    </select>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Isi Artikel Lengkap</label>
                    <textarea id="content" name="content" rows="12" required 
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">{{ old('content', $article->content) }}</textarea>
                </div>

                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Ganti Gambar Thumbnail (Opsional, Max 2MB)</label>
                    @if($article->thumbnail)
                        <div class="mb-3 text-xs text-slate-450 flex items-center gap-2 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl border">
                            <i class="fa-solid fa-paperclip text-indigo-500"></i>
                            <span>Thumbnail saat ini: {{ basename($article->thumbnail) }}</span>
                        </div>
                    @endif
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-950 dark:file:text-indigo-400 focus:ring-indigo-500">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-850">
                    <a href="{{ route('articles.show', $article->id) }}" class="px-5 py-3 bg-slate-150 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-bold rounded-2xl text-xs transition">
                        Batal
                    </a>
                    <button type="submit" class="px-7 py-3 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-indigo-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
