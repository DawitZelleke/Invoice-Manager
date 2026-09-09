<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/invoices');

Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoices', 'index')->name('invoices.index');
    Route::get('/invoices/create', 'create')->name('invoices.create');
    Route::post('/invoices', 'store')->name('invoices.store');
    Route::get('/invoices/{invoice}/edit', 'edit')->name('invoices.edit');
    Route::put('/invoices/{invoice}', 'update')->name('invoices.update');
    Route::delete('/invoices/{invoice}', 'destroy')->name('invoices.destroy');
});
