# Invoice Manager Part 2
- Name: Dawit Zelleke
- Student Number: 041177199
- Section Number: CST8257



# Invoice Manager Part 2

## Observation Questions

### What challenges did you have refactoring the project using GitHub Copilot? What extra instructions or context did you need to provide?

One challenge I had was changing the project from using separate pages to using one `index.php` page. At first, it was easier to understand the project when there was one page for draft invoices, one page for pending invoices, and one page for paid invoices. I had to make sure the status came from the URL using `$_GET`.

I needed to give GitHub Copilot extra instructions about using the query string. For example, I had to explain that links like `index.php?status=draft` should show only draft invoices. I also had to make sure it understood that the old `draft.php`, `pending.php`, and `paid.php` files were no longer needed.

Another challenge was using sessions to keep the new invoices saved. The invoices would reset if they were only added to the normal array. Using `$_SESSION` fixed that problem because it keeps the invoices during the user's session.

### In PHP, what does the term "superglobals" mean and how do those variables differ from other variables. Provide a list of "superglobals" and when to use them?

In PHP, superglobals are special built-in variables that are always available. They can be used from anywhere in the PHP file, even inside functions. This makes them different from regular variables because regular variables usually only work in the place where they were created.

Common PHP superglobals include `$_GET`, `$_POST`, `$_SESSION`, `$_SERVER`, `$_COOKIE`, `$_FILES`, `$_REQUEST`, `$_ENV`, and `$GLOBALS`. `$_GET` is used when information is sent through the URL. `$_POST` is used when a form sends data after being submitted.

`$_SESSION` is used when information needs to stay available while the user is using the website. `$_SERVER` gives information about the server and request. `$_FILES` is used when users upload files through a form.

### What improvements or changes would you make to the project either in additional features or improvement in the existing code?

One improvement I would make is adding form validation. Right now, the form uses basic HTML required fields, but stronger PHP validation would be better. This would make sure users enter a real email, a proper amount, and a correct invoice status.

Another improvement would be adding edit and delete features. This would let the user fix invoice information or remove invoices they no longer need. It would make the project feel more like a complete invoice manager.

I would also improve the code by moving repeated code into separate files. For example, the header, navigation, and invoice functions could be placed in their own files. This would make the project cleaner and easier to update later.