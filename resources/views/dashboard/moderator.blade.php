<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Panel Moderator') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-450 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Laporan Pending</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $pendingReports->count() }}</p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-450 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Ditinjau</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $reviewedReports->count() }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-450 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Laporan Selesai</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $resolvedReportsCount }} / {{ $totalReports }}</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Manage Reports -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Pending Reports Section -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-shield-halved text-rose-500"></i>Antrean Laporan Masuk (Pending)
                    </h4>
                    
                    <div class="space-y-4">
                        @forelse($pendingReports as $report)
                            <div class="p-5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-2xl space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-2xs font-extrabold bg-rose-100 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 uppercase tracking-wide">
                                        {{ $report->report_type }}
                                    </span>
                                    <span class="text-4xs text-slate-450">{{ $report->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-sm text-slate-750 dark:text-slate-350 leading-relaxed font-medium">
                                    {{ $report->description }}
                                </p>
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-3 border-t border-slate-200/50 dark:border-slate-800/50 text-xs">
                                    <p class="text-slate-500">
                                        Reporter: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $report->reporter ? $report->reporter->name : 'Anonim' }}</span>
                                    </p>
                                    
                                    <!-- Action Dropdowns -->
                                    <form action="{{ route('reports.status', $report->id) }}" method="POST" class="flex gap-2 w-full sm:w-auto">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="bg-white dark:bg-slate-900 border border-slate-250 dark:border-slate-850 rounded-xl px-3 py-1.5 text-xs font-bold shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="pending" selected>Ubah Status</option>
                                            <option value="reviewed">Tinjau Laporan</option>
                                            <option value="resolved">Selesaikan</option>
                                        </select>
                                        <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl font-bold transition">Update</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 dark:text-slate-400 text-sm py-6">Tidak ada laporan pending saat ini. Bagus!</p>
                        @endforelse
                    </div>
                </div>

                <!-- Reviewed Reports Section -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-spinner text-amber-500"></i>Laporan Sedang Ditinjau (Reviewed)
                    </h4>
                    
                    <div class="space-y-4">
                        @forelse($reviewedReports as $report)
                            <div class="p-5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-2xl space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-2xs font-extrabold bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 uppercase tracking-wide">
                                        {{ $report->report_type }}
                                    </span>
                                    <span class="text-4xs text-slate-450">{{ $report->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-sm text-slate-750 dark:text-slate-350 leading-relaxed font-medium">
                                    {{ $report->description }}
                                </p>
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-3 border-t border-slate-200/50 dark:border-slate-800/50 text-xs">
                                    <p class="text-slate-500">
                                        Reporter: <span class="font-bold text-slate-700 dark:text-slate-200">{{ $report->reporter ? $report->reporter->name : 'Anonim' }}</span>
                                    </p>
                                    
                                    <form action="{{ route('reports.status', $report->id) }}" method="POST" class="flex gap-2 w-full sm:w-auto">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="bg-white dark:bg-slate-900 border border-slate-250 dark:border-slate-850 rounded-xl px-3 py-1.5 text-xs font-bold shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="reviewed" selected>Ubah Status</option>
                                            <option value="pending">Kembalikan ke Pending</option>
                                            <option value="resolved">Selesaikan</option>
                                        </select>
                                        <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl font-bold transition">Update</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 dark:text-slate-400 text-sm py-6">Tidak ada laporan dalam tinjauan.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Moderate Forums -->
            <div>
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-flag text-rose-500"></i>Diskusi Terlaporkan
                    </h4>
                    
                    <div class="space-y-4">
                        @forelse($reportedForums as $repForum)
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-2xl">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="px-2 py-0.5 rounded text-3xs font-extrabold bg-rose-100 text-rose-700">
                                        {{ $repForum->reports_count }} Laporkan
                                    </span>
                                    <span class="text-4xs text-slate-450">{{ $repForum->created_at->diffForHumans() }}</span>
                                </div>
                                <h5 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1 hover:text-indigo-500 transition">
                                    <a href="{{ route('forums.show', $repForum->id) }}">{{ $repForum->title }}</a>
                                </h5>
                                <p class="text-3xs text-slate-450 mt-1">Author: {{ $repForum->user->name }}</p>
                                
                                <div class="flex gap-2 mt-3 pt-2 border-t border-slate-200/50 dark:border-slate-800/50">
                                    <a href="{{ route('forums.show', $repForum->id) }}" class="flex-1 text-center py-1 bg-white hover:bg-slate-100 border rounded-lg text-3xs font-bold text-slate-700 transition">
                                        Tinjau
                                    </a>
                                    
                                    <form action="{{ route('forums.destroy', $repForum->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus diskusi terlaporkan ini?')" class="w-full text-center py-1 bg-rose-50 hover:bg-rose-100 rounded-lg text-3xs font-bold text-rose-600 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 dark:text-slate-400 text-xs py-6">Bersih! Tidak ada diskusi terlaporkan saat ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
