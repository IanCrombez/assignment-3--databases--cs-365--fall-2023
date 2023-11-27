# Fall 2023 Principles of Databases — Assignment 3

* **Do not start this project until you’ve read and understood these instructions. If something is not clear, ask.**

---

## ❖・Introduction・❖

In assignment 2, you created a database consisting of flat tables, or relations, of passwords associated with web sites/apps. For this assignment, you’ll extend on assignment 2 by doing the following:

* Separating the content in those flat relations into separate relations;
* Establishing relationships between those relations;
* Adding key constraints;
* Drawing out the relationships between those relations using the ER model; and,
* Adding an interactive layer using HTML forms and PHP.

In essence, you’re creating an elaborate, MVC-based application of assignment 2 using a WAMP/MAMP stack.

---

## ❖・Requirements・❖

The database should handle the following operations, all via HTML forms:

* **Search** every entry in the database, wrapping the result in a table. If the search fails, indicate this to the user.
* **Update** any column/attribute using another distinct column/attribute as a pattern match.
* **Insert** new entries into the database. Each new entry should accept site/app name, URL, email address, username, password, and a comment. The comment field in the HTML form should use the HTML [`textarea`](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea) element in lieu of the `input` element.
* **Delete** an entry from the database based on a pattern match with another distinct column/attribute.

You’ll also need to draw a diagram of your entity-relationship, or ER, model. You may do this on paper and then take a picture, or you may use any digital tool you like.

---
## ❖・Due・❖

Friday, 8 December 2023, at 5:00 PM.

---

## ❖・Grading・❖

| Item                     | Points |
|--------------------------|:------:|
| *Project implementation* | `33`   |
| *Syntax quality*         | `33`   |
| *Following instructions* | `33`   |

---

## ❖・Submission・❖

**NO late submissions will be accepted.**

You will need to issue a pull request back into the original repo, the one from which your fork was created for this project. See the **Issuing Pull Requests** section of [this site](http://code-warrior.github.io/tutorials/git/github/index.html) for help on how to submit your assignment.

**Note**: This assignment may **only** be submitted via GitHub. **No other form of submission will be accepted**.
