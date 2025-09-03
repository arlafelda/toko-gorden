<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Password Akun Anda Telah Diubah')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Kami ingin memberitahu bahwa password akun Anda telah berhasil diubah.')
            ->line('Jika Anda tidak merasa melakukan perubahan ini, segera hubungi admin kami.')
            ->salutation('Salam, Toko Gorden');
    }
}
