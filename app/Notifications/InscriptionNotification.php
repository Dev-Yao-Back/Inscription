<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscriptionNotification extends Notification
{
    use Queueable;


    protected $etat;
    protected $etudiant;

    /**
     * Create a new notification instance.
     */
    public function __construct($etat, $etudiant)
    {
        $this->etat = $etat;
        $this->etudiant = $etudiant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)

                    ->action('Notification Action', url('/'))

                    ->subject('Notification d\'inscription')
                    ->greeting('Bonjour,')
                    ->line("L'inscription de l'étudiant(e) {$this->etudiant->nom} a été {$this->etat}.")
                    ->line('Merci de consulter le système pour plus de détails.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
             'message' => "L'inscription de l'étudiant(e) {$this->etudiant->nom} a été {$this->etat}.",
        ];
    }
}