<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoActualizado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mesaId;
    public $message;
    public $tipoCambio;

    /**
     * Create a new event instance.
     */
    public function __construct($mesaId, $message = null, $tipoCambio = 'actualizado')
    {
        $this->mesaId = $mesaId;
        $this->message = $message;
        $this->tipoCambio = $tipoCambio;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('pedidos-canal'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'mesaId' => $this->mesaId,
            'message' => $this->message,
            'tipoCambio' => $this->tipoCambio,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'pedido.actualizado';
    }
}
