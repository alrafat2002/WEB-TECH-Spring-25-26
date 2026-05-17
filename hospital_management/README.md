# Hospital Management System

A PHP + MySQL hospital management system built with procedural mysqli and no frameworks.

## Setup

1. Import `database.sql` into phpMyAdmin (creates `hospital_db` with all tables).
2. Place the project folder inside your `htdocs` or `www` directory (XAMPP / WAMP).
3. Open `http://localhost/hospital_management/` in your browser.

## Default Login

| Role  | Username | Password |
|-------|----------|----------|
| Admin | admin    | admin123 |

## Roles

- **Admin** — Manages doctor accounts (add, edit, delete, search)
- **Doctor** — Manages patient records (add, edit, delete, search)
- **Doctor** — Can also self-register via the Register page

## Features

- Role-based authentication with session management
- "Remember me" cookie
- Live AJAX search (no page reload)
- Null/empty-field validation on add and update
- Prepared statements throughout (no SQL injection)
- Password hashing with `password_hash()`

## File Structure

```
hospital_management/
├── index.php          ← Front controller / router
├── config.php         ← DB connection + admin seed
├── models.php         ← All database queries
├── controllers.php    ← Business logic per role
├── style.css          ← Full stylesheet
├── database.sql       ← DB schema (import once)
└── views/
    ├── login.php      ← Login page
    ├── register.php   ← Doctor self-registration
    ├── admin.php      ← Admin dashboard (manage doctors)
    └── doctor.php     ← Doctor dashboard (manage patients)
```
