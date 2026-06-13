<?php

namespace App\Mail;

use App\Models\SalesInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SalesInvoice $invoice)
    {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('info.ryo.brand@gmail.com', 'RYO Brand'),
            subject: 'Your RYO Invoice #' . $this->invoice->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-invoice',
            with: [
                'invoice' => $this->invoice,
            ],
        );
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Failed to send order invoice email.', [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'error' => $exception->getMessage(),
        ]);
    }
}
