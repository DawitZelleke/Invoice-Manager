@extends('layouts.app')

@section('title', 'Invoices')

@section('action')
    <a class="btn btn-primary" href="{{ route('invoices.create') }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2.6" stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14M5 12h14"></path>
        </svg>
        New invoice
    </a>
@endsection

@section('content')
    <section class="hero">
        <div>
            <p class="hero-caption">
                {{ $current === 'all' ? 'Everything on the books' : ucfirst($current) . ' invoices' }}
            </p>
            <h1 class="hero-figure" data-countup="{{ $total }}">${{ number_format($total) }}</h1>
            <p class="hero-note" data-tally="{{ $invoices->count() }} {{ $invoices->count() === 1 ? 'invoice' : 'invoices' }} in this view">
                {{ $invoices->count() }} {{ $invoices->count() === 1 ? 'invoice' : 'invoices' }} in this view
            </p>
        </div>

        @if ($grand > 0)
            <div class="split">
                <div class="split-bar" role="img"
                     aria-label="How the {{ number_format($grand) }} dollar book splits by status">
                    @foreach ($breakdown as $slice)
                        <span class="is-{{ $slice->status }}"
                              style="--w: {{ max($slice->total, 1) }}; --i: {{ $loop->index }}"></span>
                    @endforeach
                </div>

                <ul class="split-key">
                    @foreach ($breakdown as $slice)
                        <li>
                            <a href="{{ route('invoices.index', ['status' => $slice->status]) }}">
                                <span class="dot" style="background: var(--{{ $slice->status }}, var(--slate))"></span>
                                {{ ucfirst($slice->status) }}
                                <b>${{ number_format($slice->total) }}</b>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </section>

    <div class="controls">
        <nav class="segmented" aria-label="Filter by status">
            <a href="{{ route('invoices.index') }}"
               @if ($current === 'all') aria-current="page" @endif>All</a>
            @foreach ($statuses as $status)
                <a href="{{ route('invoices.index', ['status' => $status->status]) }}"
                   @if ($current === $status->status) aria-current="page" @endif>{{ ucfirst($status->status) }}</a>
            @endforeach
        </nav>

        @if ($invoices->isNotEmpty())
            <div class="finder">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-3.5-3.5"></path>
                </svg>
                <label class="visually-hidden" for="finder">Search invoices</label>
                <input id="finder" type="search" data-finder autocomplete="off"
                       placeholder="Search client, email, number">
            </div>
        @endif
    </div>

    @if ($invoices->isEmpty())
        <div class="empty">
            <svg class="bob" width="52" height="52" viewBox="0 0 24 24" fill="none"
                 stroke="var(--grape)" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                <path d="M6 3h12v18l-3-2-3 2-3-2-3 2Z"></path>
                <path d="M9.5 8h5M9.5 12h5"></path>
            </svg>
            <h2>Nothing here yet</h2>
            <p>
                {{ $current === 'all'
                    ? 'Your ledger is empty. Add the first invoice and it shows up here.'
                    : 'No invoices are marked ' . $current . ' right now.' }}
            </p>
            <a class="btn btn-primary" href="{{ route('invoices.create') }}">Add an invoice</a>
        </div>
    @else
        <table class="ledger">
            <thead>
                <tr>
                    <th scope="col">Invoice</th>
                    <th scope="col">Client</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="num">Amount</th>
                    <th scope="col"><span class="visually-hidden">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr style="--accent: var(--{{ $invoice->status->status ?? 'slate' }}, var(--slate)); --i: {{ $loop->index }}"
                        data-row="{{ strtolower($invoice->number . ' ' . $invoice->client . ' ' . $invoice->email) }}">
                        <td class="ref">{{ $invoice->number }}</td>
                        <td>
                            <span class="client">{{ $invoice->client }}</span>
                            <span class="email">{{ $invoice->email }}</span>
                        </td>
                        <td>
                            <span class="tag {{ $invoice->status->status ?? '' }}">
                                {{ ucfirst($invoice->status->status ?? 'unknown') }}
                            </span>
                        </td>
                        <td class="num amount">${{ number_format($invoice->amount) }}</td>
                        <td>
                            <div class="row-actions">
                                <a class="icon-btn" href="{{ route('invoices.edit', $invoice) }}"
                                   aria-label="Edit invoice {{ $invoice->number }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M4 20h4l10.5-10.5a2.1 2.1 0 0 0-3-3L5 17v3Z"></path>
                                        <path d="M14.5 6.5l3 3"></path>
                                    </svg>
                                </a>

                                {{-- With JS this opens the dialog below; without it, the
                                     browser's own confirm still guards the delete. --}}
                                <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
                                      data-delete-form data-number="{{ $invoice->number }}"
                                      onsubmit="return confirm('Delete invoice {{ $invoice->number }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="icon-btn danger" type="submit"
                                            aria-label="Delete invoice {{ $invoice->number }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M4 7h16M9.5 7V5h5v2M6.5 7l1 13h9l1-13"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <dialog data-confirm>
        <h2>Delete this invoice?</h2>
        <p>Invoice <strong data-confirm-number></strong> is removed from the ledger for good.</p>
        <form method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="dialog-actions">
                <button class="btn btn-quiet" type="button" data-close>Keep it</button>
                <button class="btn btn-danger" type="submit">Delete invoice</button>
            </div>
        </form>
    </dialog>
@endsection
