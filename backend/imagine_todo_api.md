**Project:** Imagine Todo API

**Objective:**
Build a simple RESTful API for task management. The API should allow users to create, read, update, and delete tasks. Each task should have a title, description, due date, and status (e.g., "todo," "in progress," or "done"). The API should also support filtering tasks by status and due date.

**Requirements:**

- Implement proper data validation and error handling.
- Implement token-based authentication for user access. The API should allow users to register, login, and get a token to access protected endpoints.
- Implement endpoints that allows the user to do the following actions:
  - Create a new user account.
  - Obtain a token by providing valid credentials.
  - Create a new task.
  - Retrieve a list of all tasks.
  - Retrieve details of a specific task by its ID.
  - Edit a specific task by its ID.
- **Bonus:** Task Assignment and User Management:
  - Implement the ability to assign tasks to specific users. Tasks should have an "assignee" field that refers to the user responsible for completing the task.
  - Enhance the authentication system to differentiate between regular users and admin users. Admin users should have the ability to view, update, and delete any task, while regular users can only view, update, and delete their assigned tasks.

**Tools:**

- Use any backend programming language and framework of your choice (e.g., Python with Flask, Node.js with Express, Ruby on Rails, etc.).
- Use a database of your choice (e.g., SQLite, PostgreSQL, MySQL) to store the tasks.

**Evaluation Criteria:**

- Functionality: Does the API meet the specified requirements? Does it operate correctly without significant bugs?
- Code Quality: Is the code well-structured, readable, and maintainable? Does it adhere to best practices?
- Error Handling: Does the API gracefully handle errors and provide informative feedback to the user?

Note: Please don't hesitate to reach out if you have any questions or require clarification regarding any aspect of the assignment. Good luck!
