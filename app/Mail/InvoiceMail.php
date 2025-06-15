<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build(): static
    {
        return $this->subject('Your Monthly Invoice')
            ->view('emails.invoice')
            ->attach(Storage::disk('public')->path($this->invoice->pdf_path));
    }
}
