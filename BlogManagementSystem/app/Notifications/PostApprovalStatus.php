<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;

class PostApprovalStatus extends Notification
{
    use Queueable;

    protected $post;
    protected $status;

    public function __construct(Post $post, $status)
    {
        $this->post = $post;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Post Approval Status Update')
            ->line('Your post titled "' . $this->post->title . '" has been ' . $this->status . '.')
            ->action('View Post', url('/posts/' . $this->post->id))
            ->line('Thank you for using our application!');
    }
}
