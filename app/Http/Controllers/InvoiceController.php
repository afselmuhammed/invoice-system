<?php

namespace App\Http\Controllers;

use App\Jobs\DispatchInvoicesJob;
use App\Jobs\GenerateAndSendInvoiceJob;
use App\Models\Customer;
use Illuminate\Http\Request;
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

    public function generateAll()
    {
        if (!Customer::exists()) {
            return redirect()->back()->with('message', 'No customers found.');
        }

        try {
            DispatchInvoicesJob::dispatch();
            Log::info("DispatchInvoicesJob dispatched.");
            session()->flash('message', 'Invoice generation has been started.');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Invoice generation failed: ' . $e->getMessage());
            return redirect()->back()->with('message', 'Failed to start invoice generation.');
        }
    }
}
