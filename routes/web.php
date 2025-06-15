<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/generate-invoice', [InvoiceController::class, 'generateAllInvoices']);

Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
Route::post('/generate-invoice', [InvoiceController::class, 'generateAll'])->name('invoices.generate');
Route::get('/invoices/statuses', [InvoiceController::class, 'statuses'])->name('invoices.statuses');
