
# Invoice Manager

A full-stack invoicing app built with Laravel 11 and Blade. Create, edit, filter, and delete invoices stored in a local SQLite database — no cloud dependencies, no JavaScript framework.

![Invoice Manager screenshot — list view showing the total count-up, status split bar, and card rows](screenshot.png)

---

## Features

- Full CRUD on invoices (number, client, email, amount, status)
- Filter by status with a sliding pill nav — built with the View Transitions API across normal page loads
- Client-side search that filters rows live without a round trip
- Auto-generated invoice numbers (5 capital letters, unique, re-rollable on the form)
- Live receipt preview that updates as you type
- Light and dark mode, remembered across sessions with no flash on load
- Accessible: keyboard-navigable, focus-visible outlines, `prefers-reduced-motion` respected

## Stack

| Layer | Tech |
|---|---|
| Framework | Laravel 11 |
| Views | Blade templates |
| Database | SQLite (via Eloquent) |
| Styles | Vanilla CSS — `@property`, `color-mix()`, `:has()`, scroll-driven animations, `@starting-style` |
| JS | No framework — ~200 lines of plain JS for count-up, live search, dialog, and the receipt preview |
| Fonts | [Bricolage Grotesque](https://fonts.google.com/specimen/Bricolage+Grotesque) + [Public Sans](https://fonts.google.com/specimen/Public+Sans) |

## Local setup

```bash
git clone https://github.com/DawitZelleke/invoice-manager.git
cd invoice-manager

cp .env.example .env
php artisan key:generate
```

Point Laravel at a database that has the `statuses` and `invoices` tables. If you have the original SQLite file:

```bash
cp /path/to/invoice_manager.sqlite database/database.sqlite
```

Or create an empty one and seed the three statuses:

```bash
touch database/database.sqlite
php artisan migrate
php artisan tinker
>>> App\Models\Status::insert([['status'=>'draft'],['status'=>'pending'],['status'=>'paid']]);
```

Then start the dev server:

```bash
php artisan serve
# → http://127.0.0.1:8000
```

No `npm install` or Vite build needed. The CSS and JS are static files in `public/`.

## Project structure (relevant parts)

```
app/Http/Controllers/InvoiceController.php   CRUD + status breakdown for the split bar
app/Models/Invoice.php                        scopeOfStatus(), generateNumber()
app/Models/Status.php
resources/views/layouts/app.blade.php        Shell, masthead, theme toggle, toast
resources/views/invoices/index.blade.php     List, hero, filters, dialog
resources/views/invoices/form.blade.php      Add / edit form, live preview
public/css/app.css                           All styles (~700 lines, no preprocessor)
public/js/app.js                             Behaviour: theme, count-up, search, dialog, preview
database/database.sqlite                     Local SQLite file (gitignored)
```

## Known limitations

- No authentication — the app is open to anyone who can reach the URL
- `invoices.status_id` has no foreign-key constraint at the database level (the controller validates it with `exists:statuses,id`)
- Neither table has `created_at` / `updated_at` columns, so both models set `public $timestamps = false`
- Cross-document view transitions and the scroll-progress rail require a Chromium browser; other browsers get a normal page load

## Built for

CST8257 — Web Application Development (PHP) / Web Development and Internet Applications, Algonquin College, 2025