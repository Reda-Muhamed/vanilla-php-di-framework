
```markdown
# Native PHP Clinic Architecture 

A full-stack Clinic Management System built from scratch without any major frameworks. This project serves as a practical demonstration of **Clean Architecture**, **SOLID principles**, and **Design Patterns** using Native PHP 8 and Vanilla JavaScript.

##  Architectural Highlights

Unlike typical beginner projects, this application implements enterprise-level patterns from scratch:

* **Custom Dependency Injection (DI) Container:** Built using PHP's `Reflection` API to handle auto-wiring and dependency resolution.
* **Repository Pattern:** Decouples the data access layer from business logic, ensuring controllers remain clean and database-agnostic.
* **Domain-Driven Entities:** Uses pure PHP classes and Enums to represent core business models (`User`, `Appointment`) independently of the database structure.
* **Custom Routing Engine:** A lightweight, middleware-supported router to handle HTTP requests.
* **Secure Authentication:** Implements custom JWT generation and verification, securely storing tokens in `HttpOnly` Cookies with `SameSite=Lax` to prevent XSS and CSRF attacks.

## 🛠️ Tech Stack

* **Backend:** Native PHP 8.1+ (Strict Typing, Enums, Constructor Property Promotion)
* **Database:** MySQL (using PDO with Prepared Statements)
* **Frontend:** Vanilla JavaScript (ES6+), HTML5, CSS3 (Custom Variables, Flexbox)
* **Communication:** Fetch API (AJAJ/AJAX) with secure credential inclusion.

## ⚙️ How to Run Locally

### 1. Database Setup
1. Create a MySQL database named `clinic_db`.
2. Import the provided `database.sql` file to generate the tables.

### 2. Backend Setup
1. Navigate to the `clinic-backend` directory.
2. Run `composer install` to install the `vlucas/phpdotenv` package.
3. Copy `.env.example` to `.env` and fill in your database credentials and a secure `JWT_SECRET`.
4. Start the PHP built-in server:
   ```bash
   php -S localhost:9000 -t public
   ```

### 3. Frontend Setup
1. Open the `clinic-frontend` folder in your code editor.
2. Ensure the `API_BASE_URL` in `js/auth.js` and `js/app.js` points to `http://localhost:9000`.
3. Serve the frontend using an extension like VS Code Live Server (usually runs on port 5500).

## 👨‍💻 About the Developer

Developed by **Reda Mohamed**.
I am a Computer Science student with a strong passion for backend engineering, problem-solving, and contributing to open-source projects. I focus on building scalable systems, high-concurrency architectures, and mastering the fundamentals "under the hood" before relying on frameworks. 
```