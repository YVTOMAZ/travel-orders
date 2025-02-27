<?php

namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $travelOrder;

    public function __construct(TravelOrder $travelOrder)
    {
        $this->travelOrder = $travelOrder;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Atualização de Status da Ordem de Viagem')
        ->line("Sua ordem de viagem para {$this->travelOrder->destination} foi atualizada para: {$this->travelOrder->status}.")
        ->action('Visualizar Ordem', url("/travel-orders/{$this->travelOrder->id}"))
        ->line('Obrigado por utilizar nosso serviço!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id'    => $this->travelOrder->id,
            'status'      => $this->travelOrder->status,
            'destination' => $this->travelOrder->destination,
        ];
    }
}
