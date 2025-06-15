<?php

namespace App\Http\Controllers;

use App\Events\InvoiceBatchCompleted;
use App\Jobs\GenerateAndSendInvoiceJob;
use App\Jobs\GenerateInvoicePdf;
use App\Jobs\SendInvoiceEmailJob;
use App\Models\Customer;
use App\Services\InvoiceGeneratorService;
use Illuminate\Bus\Batch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index()
    {
        $customers = Customer::with('latestInvoice')->paginate(500);
        return view('invoices.landing', compact('customers'));
    }

    public function statuses()
    {
        $customers = Customer::with('latestInvoice')->paginate(500);
        return response()->json($customers);
    }

    public function generateAll(Request $request): JsonResponse
    {
        if (!Customer::exists()) {
            return response()->json(['message' => 'No customers found'], 400);
        }

        $chunkSize = config('app.invoice_chunk_size', 100);

        try {
            Customer::whereNotNull('email')
                ->chunk($chunkSize, function ($customers) {
                    foreach ($customers as $customer) {
                        GenerateAndSendInvoiceJob::dispatch($customer);
                    }
                });

            return response()->json(['message' => 'Invoice generation started']);
        } catch (\Exception $e) {
            Log::error('Invoice generation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to start invoice generation'], 500);
        }
    }
}
