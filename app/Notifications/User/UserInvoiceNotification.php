<?php

namespace App\Notifications\User;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserInvoiceNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Invoice $invoice,
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
            'body'  => "Order #{$this->invoice->num}: {$label}",
            'data'  => [
                'type'       => 'invoice',
                'event'      => $this->event,
                'order_id'   => $this->invoice->order_id,
                'invoice_id' => $this->invoice->id,
                'pdf_url'    => $this->invoice->pdf_url,
            ],
        ];
    }
}
