# Invoice Manager Part 3
- Name: Dawit Zelleke
- Student Number: 041177199
- Section Number: CST8257



# # Invoice Manager - Part 3 Observations

## What challenges did you face getting GitHub Copilot to properly incorporate form validation? How did GitHub Copilot validation code differ from what was taught in class?

One challenge I faced was making sure GitHub Copilot validated every field exactly the way the assignment required. Sometimes Copilot gave general validation, but it did not always check the specific rules, such as only allowing letters and spaces for the client name. The validation code also had to redirect the user back to the form and keep the old form data, which required sessions.

The validation code from GitHub Copilot was sometimes different from what was taught in class because it tried to use shortcuts or different helper functions. In class, the validation was more step-by-step and easier to follow. I had to adjust the code so that it matched the assignment instructions and still made sense to me.

## Did GitHub Copilot use any code that you didn't understand or made any changes that you didn't like? If so, explain. What did/would you do if there was generated code that you didn't understand?

GitHub Copilot sometimes suggested code that was more complicated than needed. For example, it sometimes used functions or patterns that worked, but were not as clear as the examples taught in class. I did not want to include code that I could not explain, because I need to understand what each part of the project does.

If Copilot generated code that I did not understand, I would break it into smaller parts and test each section. I would also compare it to class examples and rewrite it in a simpler way if needed. My goal would be to keep the code readable and make sure I can explain how the validation, sessions, update, and delete features work.

## What changes would you make to the quality of your code? How would you prompt GitHub Copilot to make those changes?

One change I would make to improve the code quality is to reduce repeated validation code. The add form and update form use similar validation rules, so it would be better to move the validation into a reusable function. This would make the project cleaner and easier to maintain.

I would prompt GitHub Copilot by saying, "Refactor this PHP invoice project so the validation logic is placed in reusable functions, but keep the code simple and beginner friendly." I would also ask Copilot to add comments only where they are useful. This would help improve the code without making it too complicated.
