<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportStatusNotification extends Notification
{
    use Queueable;

    private $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusLabel = 'Tertunda';
        if ($this->report->status === 'reviewed') {
            $statusLabel = 'Sedang Ditinjau';
        } elseif ($this->report->status === 'resolved') {
            $statusLabel = 'Telah Selesai Ditindaklanjuti';
        }

        return [
            'message' => "Laporan Anda mengenai {$this->report->report_type} kini berstatus: \"{$statusLabel}\"",
            'report_id' => $this->report->id,
            'report_status' => $this->report->status,
            'type' => 'report_update',
        ];
    }
}
