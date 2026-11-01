# MedicHub — Clinic Management System

A web-based clinic management project developed for **CSC264: Introduction to Web and Application** at Universiti Teknologi MARA (UiTM).

## Overview
MedicHub explores clinic workflows for three user roles: **patients, doctors, and administrators**. The repository contains PHP pages, HTML interfaces, and database-backed features.

## Features represented in the source code
- Role-based login and dashboards for patients, doctors, and administrators
- Patient registration and profile management
- Appointment viewing and updates
- Doctor profiles and availability
- Medical records and medicine inventory
- Medical certificate and PDF-related pages

> This is an academic project, not a production healthcare system. Some linked pages may be missing from the repository, and end-to-end functionality has not been re-tested.

## Technologies
- PHP and HTML/CSS/JavaScript
- MySQL via PHP PDO
- Web browser and a local PHP/MySQL server (for example, XAMPP)

## Local setup (requires additional database setup)
1. Clone this repository and place it under your local PHP web server's document root (for XAMPP, typically `htdocs/MedicHubSystem`).
2. Start Apache and MySQL.
3. Create a local MySQL database named `medichubdb`.
4. Review `dbconn.php` and adjust the database host, username, and password for your local environment.
5. **Restore or create the required database tables and sample records.** The repository does not currently include a SQL schema or seed file, so a working login cannot be guaranteed from the code alone.
6. Open `http://localhost/MedicHubSystem/mainPageForm.html` in your browser.

The code references tables including `login`, `usertype`, `patient`, `doctor`, `appointment`, `prescription`, and `medication`. Their full schemas must be confirmed before running.

## Key files
| File | Purpose |
| --- | --- |
| `dbconn.php` | PDO database connection |
| `login.php` | Role-based login handling |
| `registerPat.php` | Patient registration |
| `AppointmentPage(patient).php` | Patient appointment information |
| `medicineInventory(admin).php` | Medication inventory view |
| `homePageAdmin.html`, `homePageStaff.html`, `patientDashboard.html` | Role-specific dashboards |

## Important limitations and security notes
This is historical coursework. The current login code compares passwords directly, and the patient registration code stores a supplied password without hashing. **Do not deploy it publicly or use real patient data or credentials.** A production version would require password hashing, authorization checks, input validation, secure session handling, and comprehensive testing.

## Project context
**Course:** CSC264 — Introduction to Web and Application  
**Institution:** Universiti Teknologi MARA (UiTM)  
**Type:** Academic coursework / portfolio archive

## Future improvements
- Add a documented SQL schema and safe sample data
- Verify and repair broken page links
- Add screenshots and a short demonstration
- Improve authentication and authorization
- Test the complete appointment workflow
