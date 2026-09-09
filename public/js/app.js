/* Invoice Manager — front-end behaviour.
   No dependencies. Everything degrades to a working page if JS is off. */

const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Matches PHP's number_format() on the server so nothing flickers or disagrees.
const money = {
    format: (n) => '$' + Math.round(n).toLocaleString('en-US'),
};

/* ---------------------------------------------------------- light/dark -- */
document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
    const next = document.documentElement.dataset.theme === 'night' ? 'day' : 'night';
    document.documentElement.dataset.theme = next;
    localStorage.setItem('invoice-theme', next);
});

/* --------------------------------------------------------- count-up ----- */
/* Rolls the headline total up from zero so the page has one focal moment. */
const figure = document.querySelector('[data-countup]');

if (figure) {
    const target = Number(figure.dataset.countup);

    if (calm || target === 0) {
        figure.textContent = money.format(target);
    } else {
        const started = performance.now();
        const run = (now) => {
            const t = Math.min((now - started) / 900, 1);
            const eased = 1 - Math.pow(1 - t, 4);
            figure.textContent = money.format(Math.round(target * eased));
            if (t < 1) requestAnimationFrame(run);
        };
        requestAnimationFrame(run);
    }
}

/* ------------------------------------------------------------- search --- */
const finder = document.querySelector('[data-finder]');

if (finder) {
    const rows = [...document.querySelectorAll('[data-row]')];
    const tally = document.querySelector('[data-tally]');

    finder.addEventListener('input', () => {
        const needle = finder.value.trim().toLowerCase();
        let shown = 0;

        rows.forEach((row) => {
            const hit = !needle || row.dataset.row.includes(needle);
            row.hidden = !hit;
            if (hit) shown += 1;
        });

        if (tally) {
            tally.textContent = needle
                ? `${shown} of ${rows.length} shown`
                : tally.dataset.tally;
        }
    });
}

/* ------------------------------------------------------ delete dialog --- */
const dialog = document.querySelector('[data-confirm]');

if (dialog) {
    const label = dialog.querySelector('[data-confirm-number]');
    const form = dialog.querySelector('form');

    // Each row ships a working POST form with a native confirm as the no-JS
    // fallback. Now that JS is running, swap that out for the dialog.
    document.querySelectorAll('[data-delete-form]').forEach((rowForm) => {
        rowForm.removeAttribute('onsubmit');
        rowForm.onsubmit = null;

        rowForm.addEventListener('submit', (event) => {
            event.preventDefault();
            form.action = rowForm.action;
            label.textContent = rowForm.dataset.number;
            dialog.showModal();
        });
    });

    dialog.querySelector('[data-close]').addEventListener('click', () => dialog.close());

    // Click on the backdrop (outside the box) closes it.
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close();
    });
}

/* ---------------------------------------------------- live receipt ------ */
const form = document.querySelector('[data-invoice-form]');

if (form) {
    const out = {
        number: document.querySelector('[data-out="number"]'),
        client: document.querySelector('[data-out="client"]'),
        email: document.querySelector('[data-out="email"]'),
        amount: document.querySelector('[data-out="amount"]'),
        status: document.querySelector('[data-out="status"]'),
        card: document.querySelector('[data-receipt]'),
    };

    const write = (node, value, fallback) => {
        if (!node) return;
        node.textContent = value || fallback;
        node.classList.toggle('blank', !value);
    };

    const paint = () => {
        const data = new FormData(form);
        const chosen = form.querySelector('input[name="status_id"]:checked');

        write(out.number, (data.get('number') || '').toUpperCase(), '—————');
        write(out.client, data.get('client'), 'Nobody yet');
        write(out.email, data.get('email'), 'no email yet');

        const amount = Number(data.get('amount'));
        write(out.amount, Number.isFinite(amount) && data.get('amount') !== '' ? money.format(amount) : '', '$0');

        if (chosen) {
            write(out.status, chosen.dataset.label, '');
            out.card.style.setProperty('--accent', `var(--${chosen.dataset.status})`);
        }
    };

    form.addEventListener('input', paint);
    form.addEventListener('change', paint);
    paint();

    // Keep the invoice number field in caps so it always matches the rule.
    const number = form.querySelector('#number');
    number?.addEventListener('input', () => {
        const at = number.selectionStart;
        number.value = number.value.toUpperCase().replace(/[^A-Z]/g, '');
        number.setSelectionRange(at, at);
    });

    // Reroll a fresh five-letter number.
    form.querySelector('[data-reroll]')?.addEventListener('click', (event) => {
        const button = event.currentTarget;
        let next = '';
        for (let i = 0; i < 5; i += 1) {
            next += String.fromCharCode(65 + Math.floor(Math.random() * 26));
        }
        number.value = next;
        button.classList.add('rolling');
        setTimeout(() => button.classList.remove('rolling'), 600);
        paint();
    });
}

/* ----------------------------------------------------------- confetti --- */
/* One burst, only when an invoice was actually added. */
if (document.querySelector('[data-celebrate]') && !calm) {
    const colours = ['#6b3fd4', '#00a98f', '#f0a500', '#ff4f7b', '#9d7bff'];

    for (let i = 0; i < 26; i += 1) {
        const piece = document.createElement('i');
        piece.className = 'confetti';
        piece.style.left = `${10 + Math.random() * 80}vw`;
        piece.style.background = colours[i % colours.length];
        document.body.append(piece);

        piece.animate(
            [
                { transform: 'translateY(0) rotate(0deg)', opacity: 1 },
                {
                    transform: `translateY(80vh) rotate(${540 + Math.random() * 360}deg)`,
                    opacity: 0,
                },
            ],
            {
                duration: 1600 + Math.random() * 900,
                delay: Math.random() * 250,
                easing: 'cubic-bezier(.2,.6,.5,1)',
                fill: 'forwards',
            }
        ).finished.then(() => piece.remove());
    }
}
