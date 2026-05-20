<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Kotak Masuk Notifikasi') }}
            </h2>
            
            @if(Auth::user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition">
                        Tandai Semua Telah Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-md overflow-hidden">
            
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($notifications as $notif)
                    @php
                        $isUnread = $notif->unread();
                        $data = $notif->data;
                    @endphp
                    
                    <div class="p-5 flex items-start justify-between gap-4 transition {{ $isUnread ? 'bg-indigo-50/20' : '' }}">
                        <div class="flex items-start gap-4">
                            <!-- Icon Indicator depending on type -->
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-950 flex items-center justify-center border text-sm shadow-sm">
                                @if(($data['type'] ?? '') === 'forum_reply')
                                    <i class="fa-solid fa-reply text-indigo-500"></i>
                                @elseif(($data['type'] ?? '') === 'badge_earned')
                                    <i class="fa-solid fa-medal text-amber-500 animate-bounce"></i>
                                @elseif(($data['type'] ?? '') === 'event_joined')
                                    <i class="fa-solid fa-calendar-check text-teal-500"></i>
                                @else
                                    <i class="fa-solid fa-bell text-slate-400"></i>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm font-bold text-slate-850 dark:text-slate-200 leading-normal">
                                    {{ $data['message'] ?? 'Notifikasi baru' }}
                                </p>
                                <span class="text-4xs text-slate-450 block mt-1">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <!-- Read Trigger -->
                        @if($isUnread)
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-650 text-3xs font-extrabold rounded-lg transition" title="Tandai telah dibaca">
                                    Baca
                                </button>
                            </form>
                        @else
                            <span class="text-4xs text-slate-400 font-bold uppercase mr-2"><i class="fa-solid fa-circle-check"></i> Dibaca</span>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-500 dark:text-slate-400">
                        <i class="fa-solid fa-bell-slash text-4xl text-slate-350 mb-3"></i>
                        <p class="text-sm font-bold">Kotak masuk kosong.</p>
                        <p class="text-3xs text-slate-450 mt-1">Anda tidak memiliki notifikasi baru saat ini.</p>
                    </div>
                @endforelse
            </div>

        </div>

        <!-- Pagination -->
        <div>
            {{ $notifications->links() }}
        </div>

    </div>
</x-app-layout>
