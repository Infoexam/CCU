<?php

namespace App\Notifications;

use Hashids;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Infoexam\Eloquent\Models\Apply;

class ListingApplied extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Apply
     */
    protected $apply;

    /**
     * Create a new notification instance.
     *
     * @param Apply $apply
     */
    public function __construct(Apply $apply)
    {
        $this->apply = $apply;
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
            ->action('前往確認', url('/apply/notification?'.$this->token()))
            ->line('若您有任何問題，請儘速來信 '.config('mail.from.address').' 或電校內分機：14007')
            ->line('　');
    }

    /**
     * Get the apply token.
     *
     * @return string
     */
    protected function token()
    {
        $token = str_random(100);

        $identify = Hashids::connection()->encode(
            $this->apply->getAttribute('user_id'),
            $this->apply->getAttribute('listing_id')
        );

        $this->apply->update(['token' => $token]);

        return "token={$token}&checksum={$identify}";
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
