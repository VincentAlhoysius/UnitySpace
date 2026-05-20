<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Hasil Self-Assessment Toleransi') }}
            </h2>
            <a href="{{ route('quiz.index') }}" class="px-5 py-2 bg-indigo-650 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition">
                Asesmen Ulang
            </a>
        </div>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="grid lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Detailed Interpretation & Score Radar Chart -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Result Overview Card -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md space-y-6">
                
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 border-b pb-6 border-slate-100 dark:border-slate-850">
                    <div class="text-center sm:text-left">
                        <p class="text-xs uppercase text-slate-400 font-bold">Kategori Toleransi Anda</p>
                        <h3 class="text-3xl font-black uppercase mt-1 tracking-wider {{ $result->category === 'tinggi' ? 'text-emerald-500' : ($result->category === 'sedang' ? 'text-amber-500' : 'text-rose-500') }}">
                            {{ $result->category }}
                        </h3>
                    </div>
                    
                    <div class="w-24 h-24 rounded-full border-8 {{ $result->category === 'tinggi' ? 'border-emerald-500/20' : ($result->category === 'sedang' ? 'border-amber-500/20' : 'border-rose-500/20') }} flex items-center justify-center relative">
                        <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $result->score }}</span>
                        <span class="text-5xs text-slate-400 font-bold absolute bottom-2">/ 50</span>
                    </div>
                </div>

                <div class="space-y-4 leading-relaxed font-medium">
                    <h4 class="font-extrabold text-slate-850 dark:text-white text-base">Interpretasi Psikologis Sikap Anda</h4>
                    <p class="text-sm text-slate-650 dark:text-slate-350">
                        {{ $interpretation }}
                    </p>
                </div>
            </div>

            <!-- Radar/Bar Chart by Dimension -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md">
                <h4 class="font-extrabold text-slate-850 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-chart-radar text-indigo-500"></i>Grafik Kompetensi Kebinekaan Anda
                </h4>
                
                <!-- Radar canvas -->
                <div class="max-w-[400px] mx-auto">
                    <canvas id="dimensionRadarChart" width="300" height="300"></canvas>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Actionable Recommendations -->
        <div class="space-y-8">
            
            <!-- Recommendations box -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md space-y-6">
                <h4 class="font-extrabold text-slate-850 dark:text-white border-b pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-indigo-500"></i>Langkah Rekomendasi
                </h4>
                
                <div class="space-y-4">
                    <a href="{{ route('articles.index') }}" class="block p-4 bg-slate-50 hover:bg-indigo-50 dark:bg-slate-950 dark:hover:bg-slate-850 rounded-2xl border transition">
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Pelajari Melalui Artikel</p>
                        <p class="text-4xs text-slate-400 mt-1 leading-normal">Eksplorasi literatur hak asasi, keadilan sosial, dan pluralisme beragama.</p>
                    </a>
                    
                    <a href="{{ route('events.index') }}" class="block p-4 bg-slate-50 hover:bg-teal-50 dark:bg-slate-950 dark:hover:bg-slate-850 rounded-2xl border transition">
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Ikuti Relawan Lintas Agama</p>
                        <p class="text-4xs text-slate-400 mt-1 leading-normal">Latih empati kebinekaan Anda secara langsung dengan melakukan gotong royong.</p>
                    </a>

                    <a href="{{ route('forums.index') }}" class="block p-4 bg-slate-50 hover:bg-amber-50 dark:bg-slate-950 dark:hover:bg-slate-850 rounded-2xl border transition">
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Ajukan Topik Harmoni</p>
                        <p class="text-4xs text-slate-450 mt-1 leading-normal">Berdialog secara sehat dengan rekan mahasiswa lainnya secara inklusif.</p>
                    </a>
                </div>
            </div>

            <!-- Gamification Status Mini Card -->
            <div class="bg-gradient-to-r from-teal-550 to-teal-600 dark:from-teal-850 dark:to-teal-900 text-white rounded-3xl p-6 shadow-md relative overflow-hidden">
                <div class="absolute -right-5 -bottom-5 w-20 h-20 bg-white/10 rounded-full blur-lg"></div>
                <h4 class="font-extrabold text-base mb-2">Gamifikasi Poin</h4>
                <p class="text-xs text-teal-50 leading-relaxed font-medium">
                    Selamat! Anda baru saja mendapatkan <strong>+30 Poin</strong> atas komitmen menyelesaikan self-assessment kebinekaan.
                </p>
            </div>

        </div>

    </div>

    <!-- Chart.js Radar Rendering Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('dimensionRadarChart').getContext('2d');
            const dimensionScores = @json($dimensionScores);

            const labels = Object.keys(dimensionScores);
            const data = Object.values(dimensionScores);

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Skor Toleransi Asesmen',
                        data: data,
                        fill: true,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)', // Indigo opacity
                        borderColor: 'rgb(79, 70, 229)',
                        pointBackgroundColor: 'rgb(13, 148, 136)', // Teal
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(79, 70, 229)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            angleLines: {
                                color: document.documentElement.classList.contains('dark') ? '#334155' : '#e2e8f0'
                            },
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? '#1e293b' : '#f1f5f9'
                            },
                            pointLabels: {
                                color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#475569',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                backdropColor: 'transparent',
                                color: document.documentElement.classList.contains('dark') ? '#64748b' : '#94a3b8',
                                stepSize: 1,
                                max: 10 // Max points per dimension
                            },
                            suggestedMin: 0,
                            suggestedMax: 10
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
