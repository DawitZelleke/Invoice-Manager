@extends('layouts.app')

@section('title', $invoice->exists ? 'Edit invoice ' . $invoice->number : 'Add invoice')

@section('action')
    <a class="btn btn-ghost" href="{{ route('invoices.index') }}">All invoices</a>
@endsection

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $invoice->exists ? 'Edit invoice ' . $invoice->number : 'Add an invoice' }}</h1>
            <p>
                {{ $invoice->exists
                    ? 'Changes save straight to the ledger.'
                    : 'The number is picked for you. Roll for a new one or type your own.' }}
            </p>
        </div>
    </div>

    <div class="workbench">
        <div class="panel">
            <form method="POST" data-invoice-form
                  action="{{ $invoice->exists ? route('invoices.update', $invoice) : route('invoices.store') }}">
                @csrf
                @if ($invoice->exists)
                    @method('PUT')
                @endif

                <div class="field">
                    <label for="number">Invoice number</label>
                    <div class="control">
                        <input id="number" name="number" maxlength="5" required
                               pattern="[A-Z]{5}" placeholder=" " autocomplete="off"
                               value="{{ old('number', $invoice->number) }}">
                        <button class="reroll" type="button" data-reroll aria-label="Pick a new number">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4" y="4" width="16" height="16" rx="4"></rect>
                                <circle cx="9" cy="9" r="1.3" fill="currentColor"></circle>
                                <circle cx="15" cy="15" r="1.3" fill="currentColor"></circle>
                                <circle cx="15" cy="9" r="1.3" fill="currentColor"></circle>
                            </svg>
                        </button>
                    </div>
                    <p class="hint">Five capital letters, like BIRHN. Lowercase is converted as you type.</p>
                    @error('number') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="client">Client</label>
                    <div class="control">
                        <input id="client" name="client" required placeholder=" " autocomplete="organization"
                               value="{{ old('client', $invoice->client) }}">
                    </div>
                    @error('client') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <div class="control">
                        <input id="email" name="email" type="email" required placeholder=" " autocomplete="email"
                               value="{{ old('email', $invoice->email) }}">
                    </div>
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="amount">Amount</label>
                    <div class="control">
                        <span class="money-wrap">
                            <span class="sigil" aria-hidden="true">$</span>
                            <input class="money" id="amount" name="amount" type="number" min="0" step="1"
                                   required placeholder=" " inputmode="numeric"
                                   value="{{ old('amount', $invoice->amount) }}">
                        </span>
                    </div>
                    <p class="hint">Whole dollars, matching how the existing rows are stored.</p>
                    @error('amount') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <fieldset style="border:0; padding:0; margin:0">
                        <legend style="padding:0; margin-bottom:.5rem; font-size:.85rem; font-weight:600">Status</legend>
                        @if ($statuses->isEmpty())
                            <p class="error">
                                No statuses exist in the database, so this invoice can't be saved yet.
                                Seed the statuses table first.
                            </p>
                        @endif
                        <div class="chips">
                            @foreach ($statuses as $status)
                                <input type="radio" id="status-{{ $status->id }}" name="status_id"
                                       value="{{ $status->id }}"
                                       data-status="{{ $status->status }}"
                                       data-label="{{ ucfirst($status->status) }}"
                                       @checked(old('status_id', $invoice->status_id ?? $statuses->first()->id) == $status->id)>
                                <label for="status-{{ $status->id }}"
                                       style="--accent: var(--{{ $status->status }}, var(--slate))">
                                    {{ ucfirst($status->status) }}
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                    @error('status_id') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">
                        {{ $invoice->exists ? 'Save changes' : 'Add invoice' }}
                    </button>
                    <a class="btn btn-quiet" href="{{ route('invoices.index') }}">Cancel</a>
                </div>
            </form>
        </div>

        <aside class="preview" aria-hidden="true">
            <div class="receipt" data-receipt>
                <p class="receipt-label">Preview</p>
                <p class="receipt-ref" data-out="number">—————</p>
                <div class="receipt-line"><span>Client</span><span data-out="client">Nobody yet</span></div>
                <div class="receipt-line"><span>Email</span><span data-out="email">no email yet</span></div>
                <div class="receipt-line"><span>Status</span><span data-out="status">Draft</span></div>
                <div class="receipt-total">
                    <span>Total</span>
                    <span class="figure" data-out="amount">$0</span>
                </div>
            </div>
        </aside>
    </div>
@endsection
