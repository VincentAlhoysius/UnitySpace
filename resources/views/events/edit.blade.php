<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Ubah Event') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md">
            
            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Judul Event</label>
                    <input type="text" id="title" name="title" required value="{{ old('title', $event->title) }}" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                </div>

                <!-- Grid Category, Quota, Points -->
                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Kategori Event</label>
                        <select id="category" name="category" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                            <option value="seminar" {{ $event->category === 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="volunteer" {{ $event->category === 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                            <option value="bakti sosial" {{ $event->category === 'bakti sosial' ? 'selected' : '' }}>Bakti Sosial</option>
                            <option value="dialog lintas agama" {{ $event->category === 'dialog lintas agama' ? 'selected' : '' }}>Dialog Lintas Agama</option>
                        </select>
                    </div>

                    <!-- Quota -->
                    <div>
                        <label for="quota" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Kuota Peserta</label>
                        <input type="number" id="quota" name="quota" required min="1" value="{{ old('quota', $event->quota) }}" 
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                    </div>

                    <!-- Points Reward -->
                    <div>
                        <label for="points_reward" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Reward Poin</label>
                        <input type="number" id="points_reward" name="points_reward" required min="0" value="{{ old('points_reward', $event->points_reward) }}" 
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Grid Date & Time -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Tanggal Pelaksanaan</label>
                        <input type="date" id="date" name="date" required value="{{ old('date', $event->date) }}" 
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Waktu Mulai (WIB)</label>
                        <input type="time" id="time" name="time" required value="{{ old('time', $event->time) }}" 
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Lokasi / Tempat</label>
                    <input type="text" id="location" name="location" required value="{{ old('location', $event->location) }}" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Deskripsi Kegiatan</label>
                    <textarea id="description" name="description" rows="5" required 
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">{{ old('description', $event->description) }}</textarea>
                </div>

                <!-- Poster -->
                <div>
                    <label for="poster" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Unggah Poster Baru (Opsional, Max 2MB)</label>
                    @if($event->poster)
                        <div class="mb-3 text-xs text-slate-400 flex items-center gap-2 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800">
                            <i class="fa-solid fa-paperclip text-indigo-500"></i>
                            <span>Poster saat ini: {{ basename($event->poster) }}</span>
                        </div>
                    @endif
                    <input type="file" id="poster" name="poster" accept="image/*" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-950 dark:file:text-indigo-400 focus:ring-indigo-500">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-850">
                    <a href="{{ route('events.show', $event->id) }}" class="px-5 py-3 bg-slate-150 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-bold rounded-2xl text-xs transition">
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
