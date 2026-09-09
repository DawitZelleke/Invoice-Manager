<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $current  = $request->query('status', 'all');
        $statuses = Status::orderBy('id')->get();

        $invoices = Invoice::with('status')
            ->ofStatus($current)
            ->orderBy('id')
            ->get();

        // Totals for the whole book, so the split bar in the header stays
        // steady while you move between filters.
        $everything = Invoice::all(['amount', 'status_id']);

        $breakdown = $statuses->map(fn (Status $status) => (object) [
            'status' => $status->status,
            'count'  => $everything->where('status_id', $status->id)->count(),
            'total'  => $everything->where('status_id', $status->id)->sum('amount'),
        ]);

        return view('invoices.index', [
            'invoices'  => $invoices,
            'statuses'  => $statuses,
            'current'   => $current,
            'total'     => $invoices->sum('amount'),
            'breakdown' => $breakdown,
            'grand'     => $everything->sum('amount'),
        ]);
    }

    public function create(): View
    {
        return view('invoices.form', [
            'invoice'  => new Invoice(['number' => Invoice::generateNumber()]),
            'statuses' => Status::orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Invoice::create($this->validated($request));

        return redirect()->route('invoices.index')->with('flash', 'Invoice added.');
    }

    public function edit(Invoice $invoice): View
    {
        return view('invoices.form', [
            'invoice'  => $invoice,
            'statuses' => Status::orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($this->validated($request, $invoice));

        return redirect()->route('invoices.index')->with('flash', "Invoice {$invoice->number} saved.");
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $number = $invoice->number;
        $invoice->delete();

        return redirect()->route('invoices.index')->with('flash', "Invoice {$number} deleted.");
    }

    private function validated(Request $request, ?Invoice $invoice = null): array
    {
        $unique = 'unique:invoices,number' . ($invoice ? ',' . $invoice->id : '');

        return $request->validate([
            'number'    => ['required', 'string', 'size:5', 'regex:/^[A-Z]{5}$/', $unique],
            'client'    => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'amount'    => ['required', 'integer', 'min:0'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
        ], [
            'number.regex' => 'The invoice number must be five capital letters.',
        ]);
    }
}
