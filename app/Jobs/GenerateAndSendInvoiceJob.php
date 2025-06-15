<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\InvoiceGeneratorService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAndSendInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Customer $customer;

    public $tries = 5;
    public $backoff = 60;
    /**
     * Create a new job instance.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     */
    public function handle(InvoiceGeneratorService $invoiceGenerator)
    {
        try {
            //Generate Invoice
            Log::info("Generating invoice for customer ID: {$this->customer->id}");
            $invoice = $invoiceGenerator->generate($this->customer);
            Log::info("Invoice generated successfully for customer ID {$this->customer->id}");

            //Send invoice mail
            Log::info("Sending invoice mail for customer ID: {$this->customer->id}");
            SendInvoiceEmailJob::dispatch($invoice)->onQueue('emails')->delay(now()->addSeconds(2));
        } catch (\Exception $th) {
            Log::info("Error Generating invoice for customer ID: {$this->customer->id}");
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Generate and send mail Job failed: {$exception->getMessage()}", [
            'customer_id' => $this->customer->id ?? null,
        ]);
    }
}
