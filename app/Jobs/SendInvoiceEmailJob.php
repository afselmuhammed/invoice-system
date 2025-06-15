<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public Invoice $invoice;
    public $tries = 5;
    public $backoff = 60;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle(): void
    {
        try {
            if (!$this->invoice->customer || !$this->invoice->customer->email) {
                Log::warning('Customer or email missing', [
                    'invoice_id' => $this->invoice->id
                ]);
                return;
            }
            // Ensure relationship loaded
            $this->invoice->load('customer');

            // Queue the mailable instead of sending immediately
            Mail::to($this->invoice->customer->email)->queue(new InvoiceMail($this->invoice));
            // Update the mailsttaus value
            $this->invoice->update(['status' => 'sent']);
            Log::info("Invoice email sent to customer ID: {$this->invoice->customer->id}");
        } catch (\Throwable $th) {
            Log::error("Mail sending failed", [
                'invoice_id' => $this->invoice->id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            throw $th;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Send invoice mail Job failed: {$exception->getMessage()}", [
            'invoice_id' => $this->invoice->id ?? null,
        ]);

        $this->invoice->update(['mail_status' => 'failed']);
    }
}
