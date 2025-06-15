<?php

namespace App\Services;

use App\Jobs\SendInvoiceEmailJob;
use App\Models\Customer;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceGeneratorService
{
    public function generate(Customer $customer): Invoice
    {
        $existing = Invoice::where('customer_id', $customer->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->first();

        if ($existing) {
            \Log::info("Invoice already exists for customer ID {$customer->id}, skipping.");
            return $existing;
        }

        $invoiceData = [
            'customer' => $customer,
            'amount' => rand(100, 1000),
            'date' => now()->format('Y-m-d'),
            'invoice_number' => 'INV-' . $customer->id . '-' . now()->timestamp,
        ];

        $pdf = Pdf::loadView('invoices.template', ['invoice' => $invoiceData])
            ->setPaper('a4')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);

        $filename = "invoice_{$customer->id}_" . now()->format('Y_m_d_His') . ".pdf";
        $pdfPath = "invoices/{$filename}";
        Storage::disk('public')->put($pdfPath, $pdf->output());

        return  Invoice::create([
            'customer_id'   => $customer->id,
            'amount'        => $invoiceData['amount'],
            'invoice_number' => $invoiceData['invoice_number'],
            'pdf_path'      => $pdfPath,
            'status'        => 'generated',
        ]);
    }
}
