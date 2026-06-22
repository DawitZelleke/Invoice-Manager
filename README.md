# Invoice Manager Part 4
- Name: Dawit Zelleke
- Student Number: 041177199
- Section Number: CST8257



## Part 4 Observations

Beyond incorporating the invoice_manager.sqlite database, I refactored the project so it no longer uses the old invoices array or sessions. I created a separate database.php file so the PDO connection can be reused in different pages. I also created a validation.php file so the add and update forms can share the same validation rules. This made the project cleaner and easier to maintain.

GitHub Copilot helped with writing the PDO code, but it needed clear instructions. At first, it sometimes guessed different column names, so I had to give it the correct fields: number, client, email, amount, and status. The main challenge was making sure the site used prepared statements and did not continue using sessions or the old array. I had to prompt Copilot to use the SQLite database for create, read, update, and delete actions.

If I had to refactor the project to use PostgreSQL instead of SQLite, I would give Copilot a detailed prompt. I would say: “Refactor this PHP Invoice Manager from SQLite to PostgreSQL. Create a PostgreSQL invoices table using the same fields from the SQLite database: number, client, email, amount, and status. Update the PDO connection string for PostgreSQL, keep the same validation rules, and make sure create, read, update, and delete still work with prepared statements.” This would help Copilot understand both the database change and the project requirements.