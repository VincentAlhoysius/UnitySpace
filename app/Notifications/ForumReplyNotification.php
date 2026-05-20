<?php

namespace App\Notifications;

use App\Models\Forum;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ForumReplyNotification extends Notification
{
    use Queueable;

    private $forum;
    private $replyUser;
    private $type; // 'comment' or 'reply'

    /**
     * Create a new notification instance.
     */
    public function __construct(Forum $forum, User $replyUser, string $type)
    {
        $this->forum = $forum;
        $this->replyUser = $replyUser;
        $this->type = $type;
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
        $message = $this->type === 'reply' 
            ? "{$this->replyUser->name} membalas komentar Anda di diskusi: \"{$this->forum->title}\""
            : "{$this->replyUser->name} mengomentari diskusi Anda: \"{$this->forum->title}\"";

        return [
            'message' => $message,
            'forum_id' => $this->forum->id,
            'user_name' => $this->replyUser->name,
            'type' => 'forum_reply',
        ];
    }
}
