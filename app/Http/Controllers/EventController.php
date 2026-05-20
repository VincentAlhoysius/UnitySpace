<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::with('registrations');

        // Filter Category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        $upcomingEvents = (clone $query)->where('date', '>=', Carbon::today()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        $pastEvents = (clone $query)->where('date', '<', Carbon::today()->toDateString())
            ->orderBy('date', 'desc')
            ->get();

        $categories = [
            'seminar' => 'Seminar',
            'volunteer' => 'Volunteer',
            'bakti sosial' => 'Bakti Sosial',
            'dialog lintas agama' => 'Dialog Lintas Agama'
        ];

        return view('events.index', compact('upcomingEvents', 'pastEvents', 'categories'));
    }

    /**
     * Display event creation form.
     */
    public function create()
    {
        // Only admin & moderator can create
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403, 'Hanya Admin atau Moderator yang dapat membuat event.');
        }

        return view('events.create');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403, 'Hanya Admin atau Moderator yang dapat membuat event.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:seminar,volunteer,bakti sosial,dialog lintas agama',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'quota' => 'required|integer|min:1',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'points_reward' => 'required|integer|min:0',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Event::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'poster' => $posterPath,
            'quota' => $request->quota,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'points_reward' => $request->points_reward,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat!');
    }

    /**
     * Display a specific event.
     */
    public function show($id)
    {
        $event = Event::with(['user', 'registrations.user'])->findOrFail($id);
        
        // Carbon instance for JavaScript Countdown
        $eventDateTime = Carbon::parse($event->date . ' ' . $event->time);
        $countdownTime = $eventDateTime->toIso8601String();

        return view('events.show', compact('event', 'countdownTime'));
    }

    /**
     * Display event edit form.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        if (Auth::id() !== $event->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak diizinkan mengubah event ini.');
        }

        return view('events.edit', compact('event'));
    }

    /**
     * Update an event.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if (Auth::id() !== $event->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak diizinkan mengubah event ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:seminar,volunteer,bakti sosial,dialog lintas agama',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'quota' => 'required|integer|min:1',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'points_reward' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }
            $event->poster = $request->file('poster')->store('posters', 'public');
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'quota' => $request->quota,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'points_reward' => $request->points_reward,
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Delete an event.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if (Auth::id() !== $event->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak diizinkan menghapus event ini.');
        }

        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Join/Register for an event.
     */
    public function join($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        // Checks
        if ($event->isUserRegistered($user)) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di event ini.');
        }

        if ($event->hasReachedQuota()) {
            return redirect()->back()->with('error', 'Mohon maaf, kuota event ini sudah penuh.');
        }

        // Register
        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
        ]);

        // Award points on registration: +20 points
        $user->increment('points', 20);

        // Check badges
        $this->checkAndAwardBadges($user);

        // Notify user
        $user->notify(new \App\Notifications\EventJoinedNotification($event));

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Anda terdaftar dalam event ini dan mendapatkan +20 poin.');
    }

    /**
     * Cancel event registration.
     */
    public function cancel($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('status', 'registered')
            ->firstOrFail();

        $registration->delete();

        // Deduct points
        $user->decrement('points', 20);

        return redirect()->back()->with('success', 'Pendaftaran event berhasil dibatalkan. Poin dikurangi 20.');
    }

    /**
     * Update participant attendance.
     */
    public function markAttendance(Request $request, $registrationId)
    {
        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403);
        }

        $registration = EventRegistration::findOrFail($registrationId);
        $registration->update([
            'status' => $request->status, // 'attended' or 'registered' (absent)
        ]);

        return redirect()->back()->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    /**
     * Helper to check volunteer badges.
     */
    private function checkAndAwardBadges($user)
    {
        // Badge 1: Active Volunteer (requires 3 event registrations/attendance)
        $volunteerBadge = \App\Models\Badge::where('badge_type', 'volunteer')->first();
        if ($volunteerBadge && !$user->badges->contains($volunteerBadge->id)) {
            $regsCount = $user->registrations()->count();
            if ($regsCount >= 3) {
                $user->badges()->attach($volunteerBadge->id);
                $user->notify(new \App\Notifications\BadgeEarnedNotification($volunteerBadge));
            }
        }
    }
}
