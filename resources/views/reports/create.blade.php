<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Laporkan Isu Diskriminasi / SARA') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto" x-data="{ isAnonymous: {{ auth()->check() ? 'false' : 'true' }} }">
        
        <!-- Safety Alert Announcement -->
        <div class="bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 rounded-2xl p-5 mb-8 text-rose-800 dark:text-rose-450 text-sm leading-relaxed space-y-2">
            <h4 class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> Keamanan Pelapor Adalah Prioritas Utama Kami</h4>
            <p class="font-medium">
                UnitySpace menyediakan jalur pengaduan tepercaya bagi seluruh civitas akademika yang mengalami atau menyaksikan tindakan <strong>intoleransi, pelecehan keagamaan, diskriminasi gender/suku, atau candaan ofensif SARA</strong> di lingkungan kampus.
            </p>
            <p class="text-xs font-semibold">
                Laporan Anda akan dienkripsi secara aman dan hanya dapat diakses oleh tim Moderator dan Admin terpilih. Anda dapat mencentang opsi Lapor Secara Anonim agar identitas Anda tidak disimpan dalam database.
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md">
            
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Report Type selector -->
                <div>
                    <label for="report_type" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Tipe Pengaduan</label>
                    <select id="report_type" name="report_type" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500">
                        <option value="intoleransi">Tindakan Intoleransi Lintas Iman</option>
                        <option value="diskriminasi">Diskriminasi Suku, Agama, Ras (SARA)</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Deskripsi Kejadian Secara Kronologis</label>
                    <textarea id="description" name="description" rows="6" required placeholder="Tuliskan tanggal kejadian, lokasi spesifik, kronologi lengkap peristiwa, serta pihak-pihak yang terlibat..." 
                              class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 focus:ring-indigo-500"></textarea>
                </div>

                <!-- Evidence File upload -->
                <div>
                    <label for="evidence" class="block text-xs font-bold uppercase text-slate-550 dark:text-slate-400 mb-2">Unggah Dokumen / Bukti Pendukung (Max 5MB)</label>
                    <p class="text-4xs text-slate-400 mb-2 leading-none">Format yang diterima: JPG, PNG, PDF, WEBP.</p>
                    <input type="file" id="evidence" name="evidence" accept="image/*,application/pdf" 
                           class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-250 dark:border-slate-850 rounded-2xl py-3 px-4 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-950 dark:file:text-indigo-400 focus:ring-indigo-500">
                </div>

                <!-- Anonymity Choice (Only show if authenticated, else force true) -->
                @auth
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_anonymous" value="1" x-model="isAnonymous" class="text-rose-500 focus:ring-rose-450 mr-3 rounded">
                            <div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white">Laporkan secara Anonim</span>
                                <p class="text-4xs text-slate-400 mt-0.5 leading-normal">
                                    Identitas nama, email, dan jurusan Anda tidak akan dikaitkan dengan laporan ini. Poin reputasi (+15 Pts) tidak akan diberikan.
                                </p>
                            </div>
                        </label>
                    </div>
                @else
                    <input type="hidden" name="is_anonymous" value="1">
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border text-xs text-center text-slate-500">
                        Anda mengirimkan laporan ini tanpa melakukan login. Laporan Anda otomatis terkirim secara <strong>Anonim</strong> demi kenyamanan dan perlindungan privasi.
                    </div>
                @endauth

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-850">
                    <a href="{{ route('home') }}" class="px-5 py-3 bg-slate-150 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-bold rounded-2xl text-xs transition">
                        Batal
                    </a>
                    <button type="submit" class="px-7 py-3 bg-rose-600 hover:bg-rose-750 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-rose-500/20">
                        Kirim Laporan Pengaduan <i class="fa-solid fa-paper-plane ml-1"></i>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
