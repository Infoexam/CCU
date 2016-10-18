<?php

namespace App\Notifications;

use App\Exams\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ListingApplied extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Listing
     */
    protected $listing;

    /**
     * Create a new notification instance.
     *
     * @param Listing $listing
     */
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('資訊能力測驗預約確認信')
            ->greeting('同學您好')
            ->line('這是一封由資訊能力測驗系統發出的測驗通知信件，請點擊下方按鈕查看詳細資訊')
            ->action('前往確認', url('/'))
            ->line('若您有任何問題，請儘速回應給管理者 '.config('mail.from.address').' 或電校內分機：14007')
            ->line('　');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
