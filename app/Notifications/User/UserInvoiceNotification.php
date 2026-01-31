<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserInvoiceNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $orderNum,
        public int $invoiceId,
        public string $event,        // generated | pdf_ready
        public ?string $pdfUrl = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $label = match ($this->event) {
            'generated' => 'invoice has been generated',
            'pdf_ready' => 'invoice PDF is ready for download',
            default     => 'invoice updated',
        };

        return [
            'title' => 'invoice',
            'body'  => "Order #{$this->orderNum}: {$label}",
            'data'  => [
                'type'       => 'invoice',
                'event'      => $this->event,
                'order_id'   => $this->orderNum,
                'invoice_id' => $this->invoiceId,
                'pdf_url'    => $this->pdfUrl, 
            ],
        ];
    }
}
