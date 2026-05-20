<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Panel Administrator') }}
        </h2>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-8">
        
        <!-- Top Analytics Counter Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase tracking-wider">Total User</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalUsers }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase tracking-wider">Total Event</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalEvents }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase tracking-wider">Total Forum</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalForums }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-450 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <p class="text-3xs font-semibold text-slate-400 uppercase tracking-wider">Total Laporan</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalReports }}</p>
                </div>
            </div>
        </div>

        <!-- Charts Section (Chart.js) -->
        <div class="grid md:grid-cols-2 gap-8">
            
            <!-- Forum Categories Pie Chart -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                <h4 class="font-extrabold text-slate-850 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-indigo-500"></i>Penyebaran Kategori Forum
                </h4>
                <div class="max-w-[280px] mx-auto">
                    <canvas id="forumPieChart" width="200" height="200"></canvas>
                </div>
            </div>

            <!-- Reports Status Bar Chart -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                <h4 class="font-extrabold text-slate-850 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-teal-500"></i>Statistik Status Laporan SARA
                </h4>
                <div class="h-[250px] flex items-center justify-center">
                    <canvas id="reportBarChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Activities & Moderation Grid -->
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Management Panels -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Recent Incoming SARA Reports -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-rose-500"></i>Laporan SARA Terkini
                        </h4>
                        <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua Laporan</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                                    <th class="pb-3 font-semibold">Tipe</th>
                                    <th class="pb-3 font-semibold">Deskripsi</th>
                                    <th class="pb-3 font-semibold">Reporter</th>
                                    <th class="pb-3 font-semibold">Status</th>
                                    <th class="pb-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($recentReports as $report)
                                    <tr>
                                        <td class="py-3.5">
                                            <span class="inline-block px-2 py-0.5 rounded text-4xs font-extrabold uppercase {{ $report->report_type === 'intoleransi' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">
                                                {{ $report->report_type }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 max-w-[180px] truncate pr-4 font-medium">{{ $report->description }}</td>
                                        <td class="py-3.5 text-slate-500">{{ $report->reporter ? $report->reporter->name : 'Anonim' }}</td>
                                        <td class="py-3.5">
                                            <span class="inline-block px-2 py-0.5 rounded text-4xs font-extrabold uppercase {{ $report->status === 'pending' ? 'bg-slate-100 text-slate-600' : ($report->status === 'reviewed' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                                {{ $report->status }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 text-right">
                                            <a href="{{ route('reports.index') }}" class="text-3xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Kelola</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-slate-500 dark:text-slate-400">Belum ada laporan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Events management list -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-indigo-500"></i>Event Mendatang
                        </h4>
                        <a href="{{ route('events.create') }}" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-3xs font-bold shadow-sm transition">
                            <i class="fa-solid fa-plus mr-1"></i>Event Baru
                        </a>
                    </div>
                    
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 space-y-4">
                        @forelse($recentEvents as $event)
                            <div class="flex justify-between items-center pt-4 first:pt-0">
                                <div>
                                    <h5 class="font-bold text-slate-900 dark:text-white leading-tight">
                                        <a href="{{ route('events.show', $event->id) }}" class="hover:text-indigo-500 transition">{{ $event->title }}</a>
                                    </h5>
                                    <p class="text-3xs text-slate-450 mt-1">
                                        {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }} • Quota: {{ $event->activeRegistrations()->count() }}/{{ $event->quota }}
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('events.edit', $event->id) }}" class="p-2 bg-slate-50 hover:bg-indigo-50 dark:bg-slate-950 dark:hover:bg-slate-850 rounded-lg text-3xs font-bold text-indigo-600 dark:text-indigo-400 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus event ini?')" class="p-2 bg-rose-50 hover:bg-rose-100 rounded-lg text-3xs font-bold text-rose-600 transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 dark:text-slate-400 text-sm py-4">Belum ada event mendatang.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Leaderboard & Shortcuts -->
            <div>
                <!-- Top Leaderboard -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                    <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-award text-amber-500 text-lg"></i>Top Contributor Poin
                    </h4>
                    
                    <div class="space-y-4">
                        @foreach($leaderboard as $index => $leadUser)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-950">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $index === 0 ? 'bg-amber-400 text-white' : ($index === 1 ? 'bg-slate-350 text-white' : ($index === 2 ? 'bg-amber-700 text-white' : 'text-slate-450')) }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-[120px]">{{ $leadUser->name }}</p>
                                        <p class="text-4xs text-slate-450 truncate max-w-[120px]">{{ $leadUser->faculty }} • {{ $leadUser->role }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">{{ $leadUser->points }} Pts</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Chart rendering logic script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Forum Categories Pie Chart
            const forumCtx = document.getElementById('forumPieChart').getContext('2d');
            const forumStats = @json($forumStats);
            
            const forumLabels = Object.keys(forumStats);
            const forumData = Object.values(forumStats);

            new Chart(forumCtx, {
                type: 'pie',
                data: {
                    labels: forumLabels,
                    datasets: [{
                        data: forumData,
                        backgroundColor: [
                            '#4F46E5', // Indigo
                            '#14B8A6', // Teal
                            '#F59E0B', // Amber
                            '#EC4899', // Pink
                            '#10B981'  // Green
                        ],
                        borderWidth: 2,
                        borderColor: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#334155',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            // 2. Reports status bar chart
            const reportCtx = document.getElementById('reportBarChart').getContext('2d');
            const reportStats = @json($reportStats);
            
            const reportLabels = ['Pending', 'Reviewed', 'Resolved'];
            const reportData = [
                reportStats['pending'] || 0,
                reportStats['reviewed'] || 0,
                reportStats['resolved'] || 0
            ];

            new Chart(reportCtx, {
                type: 'bar',
                data: {
                    labels: reportLabels,
                    datasets: [{
                        label: 'Laporan SARA',
                        data: reportData,
                        backgroundColor: [
                            '#EF4444', // Red
                            '#F59E0B', // Amber
                            '#10B981'  // Green
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? '#1e293b' : '#f1f5f9'
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b',
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
