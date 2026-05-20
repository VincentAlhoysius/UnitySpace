<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-indigo-600 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Event
            </a>
            
            <div class="flex gap-2">
                @auth
                    @if(Auth::id() === $event->user_id || Auth::user()->hasRole('admin'))
                        <a href="{{ route('events.edit', $event->id) }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl text-3xs font-bold text-slate-700 dark:text-slate-200 transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>Edit Event
                        </a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
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
        
        <!-- Left 2 Columns: Poster & Full Description -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Poster Showcase Card -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-md">
                @if($event->poster)
                    <div class="max-h-[450px] w-full overflow-hidden">
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="h-60 bg-gradient-to-br from-indigo-500 to-teal-500 text-white flex flex-col items-center justify-center p-8 text-center space-y-4">
                        <i class="fa-solid fa-calendar-days text-6xl opacity-40 animate-pulse"></i>
                        <div>
                            <h3 class="text-2xl font-black">{{ $event->title }}</h3>
                            <p class="text-xs uppercase tracking-widest opacity-85 mt-2">Aksi Toleransi Nyata Mahasiswa</p>
                        </div>
                    </div>
                @endif

                <div class="p-6 md:p-8 space-y-6">
                    <span class="inline-flex px-3.5 py-1.5 rounded-xl text-2xs font-extrabold uppercase bg-teal-550/10 text-teal-600 dark:text-teal-400 tracking-wider">
                        {{ $event->category }}
                    </span>

                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-tight">
                        {{ $event->title }}
                    </h1>

                    <div class="text-slate-650 dark:text-slate-350 text-sm md:text-base leading-relaxed whitespace-pre-line font-medium">
                        {{ $event->description }}
                    </div>
                </div>
            </div>

            <!-- Attendance & Admin/Moderator Controls (Visible to Privileged users only) -->
            @auth
                @if(Auth::user()->hasRole(['admin', 'moderator']))
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md">
                        <h3 class="font-extrabold text-lg text-slate-850 dark:text-white flex items-center gap-2 mb-6 border-b pb-3">
                            <i class="fa-solid fa-clipboard-user text-indigo-500"></i>Kelola Kehadiran Peserta
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                                        <th class="pb-3">Peserta</th>
                                        <th class="pb-3">Fakultas</th>
                                        <th class="pb-3">Status</th>
                                        <th class="pb-3 text-right">Aksi Konfirmasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse($event->registrations as $reg)
                                        <tr>
                                            <td class="py-3.5">
                                                <p class="font-bold text-slate-900 dark:text-white">{{ $reg->user->name }}</p>
                                                <p class="text-4xs text-slate-450">{{ $reg->user->email }}</p>
                                            </td>
                                            <td class="py-3.5 text-slate-500">{{ $reg->user->faculty }}</td>
                                            <td class="py-3.5">
                                                <span class="inline-flex px-2 py-0.5 rounded text-4xs font-extrabold uppercase {{ $reg->status === 'attended' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ $reg->status === 'attended' ? 'Hadir' : 'Terdaftar' }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 text-right">
                                                <form action="{{ route('events.attendance', $reg->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @if($reg->status === 'registered')
                                                        <input type="hidden" name="status" value="attended">
                                                        <button type="submit" class="px-3 py-1 bg-emerald-50 hover:bg-emerald-100 rounded-lg text-3xs font-extrabold text-emerald-600 transition">
                                                            Konfirmasi Hadir
                                                        </button>
                                                    @else
                                                        <input type="hidden" name="status" value="registered">
                                                        <button type="submit" class="px-3 py-1 bg-slate-50 hover:bg-slate-100 rounded-lg text-3xs font-extrabold text-slate-650 transition">
                                                            Batalkan Hadir
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-slate-500">Belum ada peserta yang mendaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endauth

        </div>

        <!-- Right 1 Column: Date, Countdown Timer, Location Info, Join Form -->
        <div class="space-y-8">
            
            <!-- JavaScript Live Countdown Timer Card -->
            @php
                $isUpcoming = \Carbon\Carbon::parse($event->date)->isFuture() || \Carbon\Carbon::parse($event->date)->isToday();
            @endphp
            @if($isUpcoming)
                <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-3xl p-6 border border-slate-800 shadow-xl text-center">
                    <p class="text-3xs uppercase tracking-wider text-slate-400 font-extrabold mb-4">Waktu Tersisa Event</p>
                    
                    <div id="countdown" class="grid grid-cols-4 gap-2 text-white">
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-2.5">
                            <span id="cd-days" class="text-2xl font-black block">00</span>
                            <span class="text-5xs uppercase text-slate-400 font-bold">Hari</span>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-2.5">
                            <span id="cd-hours" class="text-2xl font-black block">00</span>
                            <span class="text-5xs uppercase text-slate-400 font-bold">Jam</span>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-2.5">
                            <span id="cd-minutes" class="text-2xl font-black block">00</span>
                            <span class="text-5xs uppercase text-slate-400 font-bold">Menit</span>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-2.5">
                            <span id="cd-seconds" class="text-2xl font-black block">00</span>
                            <span class="text-5xs uppercase text-slate-400 font-bold">Detik</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Event Details Information Card -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-md space-y-6">
                <h4 class="font-extrabold text-slate-850 dark:text-white mb-4">Informasi Kegiatan</h4>
                
                <div class="space-y-4 text-sm text-slate-650 dark:text-slate-350">
                    <div class="flex items-start gap-3">
                        <i class="fa-regular fa-calendar text-indigo-500 text-lg mt-0.5"></i>
                        <div>
                            <p class="text-3xs uppercase text-slate-400 font-bold">Hari & Tanggal</p>
                            <p class="font-bold text-slate-850 dark:text-slate-200">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <i class="fa-regular fa-clock text-indigo-500 text-lg mt-0.5"></i>
                        <div>
                            <p class="text-3xs uppercase text-slate-400 font-bold">Waktu Mulai</p>
                            <p class="font-bold text-slate-850 dark:text-slate-200">{{ $event->time }} WIB</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-indigo-500 text-lg mt-0.5"></i>
                        <div>
                            <p class="text-3xs uppercase text-slate-400 font-bold">Lokasi / Tempat</p>
                            <p class="font-bold text-slate-850 dark:text-slate-200">{{ $event->location }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-users text-indigo-500 text-lg mt-0.5"></i>
                        <div>
                            <p class="text-3xs uppercase text-slate-400 font-bold">Kuota Maksimal</p>
                            <p class="font-bold text-slate-850 dark:text-slate-200">{{ $event->activeRegistrations()->count() }} / {{ $event->quota }} Peserta</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-star text-teal-500 text-lg mt-0.5"></i>
                        <div>
                            <p class="text-3xs uppercase text-slate-400 font-bold">Reward Poin Kemanusiaan</p>
                            <p class="font-black text-teal-600 dark:text-teal-400">+{{ $event->points_reward }} Pts</p>
                        </div>
                    </div>
                </div>

                <!-- Joining actions -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                    @auth
                        @php
                            $userRegistered = $event->isUserRegistered(Auth::user());
                            $quotaFull = $event->hasReachedQuota();
                        @endphp

                        @if(!$isUpcoming)
                            <button disabled class="w-full py-4 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-bold text-sm rounded-2xl cursor-not-allowed">
                                Kegiatan Telah Selesai
                            </button>
                        @elseif($userRegistered)
                            <!-- Already Joined: Option to Cancel -->
                            <div class="space-y-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-450">
                                    <i class="fa-solid fa-circle-check"></i>Anda Terdaftar Peserta
                                </span>
                                <form action="{{ route('events.cancel', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Batalkan pendaftaran event ini?')" class="w-full py-4 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-sm rounded-2xl transition">
                                        Batalkan Pendaftaran
                                    </button>
                                </form>
                            </div>
                        @elseif($quotaFull)
                            <!-- Quota is full -->
                            <button disabled class="w-full py-4 bg-rose-50 dark:bg-rose-950/20 text-rose-450 dark:text-rose-500 border border-rose-200/50 font-bold text-sm rounded-2xl cursor-not-allowed">
                                Kuota Penuh
                            </button>
                        @else
                            <!-- Join Event -->
                            <form action="{{ route('events.join', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-500/20 transition">
                                    Daftar Sekarang (+{{ $event->points_reward }} Pts)
                                </button>
                            </form>
                        @endif

                    @else
                        <!-- Guest Prompt -->
                        <a href="{{ route('login') }}" class="block w-full text-center py-4 bg-indigo-650 hover:bg-indigo-750 text-white font-bold text-sm rounded-2xl shadow-lg transition">
                            Login untuk Daftar
                        </a>
                    @endauth
                </div>

            </div>

        </div>

    </div>

    <!-- Countdown Javascript Timer -->
    @if($isUpcoming)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const targetDate = new Date("{{ $countdownTime }}").getTime();

                const timerInterval = setInterval(function () {
                    const now = new Date().getTime();
                    const distance = targetDate - now;

                    if (distance < 0) {
                        clearInterval(timerInterval);
                        document.getElementById('countdown').innerHTML = "<p class='col-span-4 text-center font-bold text-sm text-indigo-400'>Event Sedang Berlangsung / Selesai!</p>";
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById('cd-days').innerText = String(days).padStart(2, '0');
                    document.getElementById('cd-hours').innerText = String(hours).padStart(2, '0');
                    document.getElementById('cd-minutes').innerText = String(minutes).padStart(2, '0');
                    document.getElementById('cd-seconds').innerText = String(seconds).padStart(2, '0');

                }, 1000);
            });
        </script>
    @endif
</x-app-layout>
