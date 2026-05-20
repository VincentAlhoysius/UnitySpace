<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Log Pengaduan Isu SARA & Diskriminasi') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="pb-4">No</th>
                        <th class="pb-4">Tipe</th>
                        <th class="pb-4">Kronologis Kejadian</th>
                        <th class="pb-4">Reporter</th>
                        <th class="pb-4">Bukti Lampiran</th>
                        <th class="pb-4">Status Kerja</th>
                        <th class="pb-4 text-right">Tindakan Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($reports as $index => $report)
                        <tr>
                            <td class="py-4 font-bold text-slate-450">{{ $reports->firstItem() + $index }}</td>
                            <td class="py-4">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-2xs font-extrabold uppercase {{ $report->report_type === 'intoleransi' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $report->report_type }}
                                </span>
                                <span class="block text-4xs text-slate-400 mt-1">{{ $report->created_at->format('d/m/y H:i') }}</span>
                            </td>
                            <td class="py-4 pr-6 max-w-[280px] leading-relaxed font-medium">
                                {{ $report->description }}
                            </td>
                            <td class="py-4 text-slate-500">
                                @if($report->reporter)
                                    <p class="font-bold text-slate-700 dark:text-slate-200">{{ $report->reporter->name }}</p>
                                    <p class="text-4xs text-slate-450">{{ $report->reporter->faculty }}</p>
                                @else
                                    <span class="inline-flex px-1.5 py-0.5 rounded text-4xs font-bold bg-slate-100 text-slate-500 uppercase">Anonim</span>
                                @endif
                            </td>
                            <td class="py-4">
                                @if($report->evidence_path)
                                    <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-50 hover:bg-indigo-50 border rounded-xl text-3xs font-extrabold text-slate-700 hover:text-indigo-600 transition">
                                        <i class="fa-solid fa-file-pdf"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-slate-400 text-3xs italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="py-4">
                                <span class="inline-flex px-2 py-0.5 rounded text-3xs font-extrabold uppercase {{ $report->status === 'pending' ? 'bg-slate-100 text-slate-600' : ($report->status === 'reviewed' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <form action="{{ route('reports.status', $report->id) }}" method="POST" class="flex items-center justify-end gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="py-1 px-2 bg-slate-50 dark:bg-slate-950 border border-slate-250 rounded-lg text-4xs font-bold">
                                        <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="reviewed" {{ $report->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                        <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-750 text-white rounded-lg text-4xs font-bold transition">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">Log pengaduan bersih dan tertib. Tidak ada data yang dilaporkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $reports->links() }}
        </div>
    </div>
</x-app-layout>
