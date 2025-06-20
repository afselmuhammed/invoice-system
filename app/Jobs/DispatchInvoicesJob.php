<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Jobs\GenerateAndSendInvoiceJob;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Log::info("DispatchInvoicesJob started.");
        foreach (Customer::whereNotNull('email')->cursor() as $customer) {
            Log::info("Dispatching GenerateAndSendInvoiceJob for customer ID: {$customer->id}");
            GenerateAndSendInvoiceJob::dispatch($customer);
        }

        \Log::info("DispatchInvoicesJob finished.");
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Generate Invoice Job failed: {$exception->getMessage()}", [
            'customer_id' => $this->customer->id ?? null,
        ]);
    }
}
