<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Self-Assessment Toleransi & Kebinekaan') }}
        </h2>
    </x-slot>

    <div class="grid lg:grid-cols-3 gap-8" x-data="{ currentStep: 0, totalQuestions: 10 }">
        
        <!-- Left 2 Cols: The Interactive Quiz Step Paging Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Step 0: Quiz Introduction -->
            <div x-show="currentStep === 0" class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md space-y-6">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-650 dark:text-indigo-400 flex items-center justify-center text-3xl shadow-sm mb-4">
                    <i class="fa-solid fa-brain"></i>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 dark:text-white leading-tight">
                    Mengapa Perlu Asesmen Toleransi?
                </h3>
                
                <div class="text-slate-600 dark:text-slate-350 text-sm leading-relaxed space-y-4 font-medium">
                    <p>
                        Asesmen Toleransi UnitySpace dirancang khusus bagi mahasiswa untuk mengukur keterbukaan pikiran, kepekaan empati, serta kenyamanan sosial dalam berinteraksi lintas iman dan suku di lingkungan kampus.
                    </p>
                    <p>
                        Metodologi ini menggunakan skala Likert 5 dimensi pilihan. Beberapa pertanyaan berupa <strong>reverse-scored</strong> (pertanyaan bermakna negatif di mana ketidaksetujuan bernilai poin tertinggi). Skor akhir Anda akan dikategorikan secara logis menjadi:
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Tinggi (40 - 50 Pts):</strong> Sangat inklusif dan berjiwa Peace Ambassador.</li>
                        <li><strong>Sedang (25 - 39 Pts):</strong> Cukup toleran namun memiliki beberapa bias stereotip.</li>
                        <li><strong>Rendah (10 - 24 Pts):</strong> Memerlukan peningkatan interaksi sosial lintas iman.</li>
                    </ul>
                    <p class="text-teal-600 dark:text-teal-400 font-extrabold flex items-center gap-1.5 pt-2">
                        <i class="fa-solid fa-circle-check text-base"></i> Anda akan mendapatkan +30 poin reputasi setelah menyelesaikan tes ini!
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-850">
                    <button @click="currentStep = 1" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-indigo-500/20 w-full sm:w-auto">
                        Mulai Asesmen Sekarang
                    </button>
                </div>
            </div>

            <!-- Steps 1 to 10: Multi-Step Quiz Questions Form -->
            <form x-show="currentStep > 0" action="{{ route('quiz.submit') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 shadow-md space-y-8">
                @csrf

                <!-- Progress Header -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                        <span>Progress Asesmen</span>
                        <span x-text="currentStep + ' / ' + totalQuestions"></span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full transition-all duration-300" :style="'width: ' + ((currentStep / totalQuestions) * 100) + '%'"></div>
                    </div>
                </div>

                <!-- Loop over the questions -->
                @foreach($quiz->questions as $index => $question)
                    @php $stepNum = $index + 1; @endphp
                    <div x-show="currentStep === {{ $stepNum }}" class="space-y-6" style="display: none;">
                        
                        <!-- Question Info -->
                        <div class="space-y-2">
                            <span class="inline-flex px-3 py-1 rounded-lg text-3xs font-extrabold uppercase bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 tracking-wider">
                                Dimensi: {{ $question->dimension }}
                            </span>
                            <h4 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white leading-snug">
                                {{ $question->question_text }}
                            </h4>
                        </div>

                        <!-- Choices as beautiful cards list -->
                        <div class="grid gap-3">
                            @foreach($question->answers as $ans)
                                <label class="relative flex items-center p-4 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-850 rounded-2xl border border-slate-200 dark:border-slate-800 cursor-pointer transition">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $ans->id }}" required class="text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <span class="text-sm font-bold text-slate-750 dark:text-slate-350">{{ $ans->option_text }}</span>
                                </label>
                            @endforeach
                        </div>

                    </div>
                @endforeach

                <!-- Step Footer Navigation buttons -->
                <div class="flex justify-between gap-4 pt-6 border-t border-slate-100 dark:border-slate-850">
                    <button type="button" @click="currentStep--" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-bold rounded-2xl text-xs transition">
                        Sebelumnya
                    </button>
                    
                    <!-- Next Question button -->
                    <button type="button" x-show="currentStep < totalQuestions" @click="currentStep++" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs transition shadow-sm">
                        Berikutnya
                    </button>

                    <!-- Final Submission button -->
                    <button type="submit" x-show="currentStep === totalQuestions" class="px-7 py-2.5 bg-teal-600 hover:bg-teal-750 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-teal-500/20">
                        Kirim Jawaban <i class="fa-solid fa-paper-plane ml-1"></i>
                    </button>
                </div>
            </form>

        </div>

        <!-- Right 1 Col: Self-Assessment History Logs -->
        <div class="space-y-6">
            
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                <h4 class="font-extrabold text-slate-850 dark:text-white flex items-center gap-2 mb-6 border-b pb-3">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i>Riwayat Pengujian Anda
                </h4>

                <div class="space-y-4 max-h-[380px] overflow-y-auto pr-1">
                    @forelse($history as $res)
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-850 dark:text-slate-200">
                                    Skor: <span class="text-indigo-650 dark:text-indigo-400 font-black">{{ $res->score }}/50</span>
                                </p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-3xs font-extrabold uppercase {{ $res->category === 'tinggi' ? 'bg-emerald-100 text-emerald-700' : ($res->category === 'sedang' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $res->category }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-4xs text-slate-450 block mb-1">{{ $res->created_at->format('d/m/y') }}</span>
                                <a href="{{ route('quiz.result', $res->id) }}" class="text-3xs font-bold text-indigo-650 hover:underline">Detail</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 text-xs py-4 leading-normal">
                            Belum ada riwayat asesmen.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
