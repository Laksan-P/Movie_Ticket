# Movie Booking System

A robust and secure web-based movie booking application built with the Laravel framework. This system provides a seamless experience for moviegoers to browse shows, select seats, and manage bookings, while providing administrators with a comprehensive dashboard to manage movies, theatres, and revenue.

## 🚀 Core Features

### User Management
- **Secure Authentication:** Built using **Laravel Jetstream**, providing a secure registration and login system.
- **My Bookings:** Users can view their booking history, check status, and perform cancellations.
- **Interactive Seat Selection:** Powered by **Livewire**, allowing users to select seats in real-time without refreshing the page.

### Admin Dashboard
- **Movie Management:** Full CRUD operations for movies including posters, descriptions, and active status.
- **Theatre & Showtime Management:** Administrators can register theatres and deploy showtime sessions.
- **Booking Oversight:** View all customer bookings, track attendance, and manage cancellations.
- **Revenue Tracking:** Advanced revenue calculation logic that includes both confirmed ticket sales and partial revenue from cancellation fees.

---

## 🔐 Security Implementation & Documentation

Security is a primary focus of this application, implemented through multiple layers:

### 1. Authentication & Authorization
- **Laravel Jetstream (Web):** Used to protect all user and administrator web routes. It handles secure session management, password hashing (Bcrypt), and protection against brute-force attacks.
- **Laravel Sanctum (API):** Provides secure, token-based authentication for the API extension. This ensures that only authorized clients can access sensitive data via mobile or third-party integrations.

### 2. Protection Layers
- **Middleware Security:** 
    - The `auth` middleware protects private user routes.
    - Specific route grouping ensures that only authenticated users can access booking and payment functions.
- **CSRF Protection:** Every state-changing request (POST/PUT/DELETE) is protected by CSRF tokens to prevent Cross-Site Request Forgery.
- **Validation:** Strict input validation is implemented in every controller using `$request->validate()`. This prevents malformed data, script injection, and ensures data integrity before it reaches the database.

### 3. Data Safety
- **Eloquent ORM:** All database interactions use Laravel’s Eloquent ORM, which automatically utilizes PDO parameter binding to protect the application against SQL Injection attacks.

---

## 🌐 API Extension & Integration

The system includes a dedicated RESTful API layer to support future mobile app integration or external services.

### API Endpoints
- **Authentication:** `POST /api/login` - Authenticates a user and returns a Sanctum bearer token.
- **Movie Registry:** `GET /api/movies` - Retrieves a list of all currently active movies.
- **User Profile:** `GET /api/user` - Returns details of the authenticated user (Requires Sanctum Token).
- **Booking Integration:** 
    - `GET /api/bookings` - Lists all bookings for the authenticated user.
    - `POST /api/bookings` - Allows booking creation via API.

---

## 📊 Business Logic: Revenue Calculation

The application uses a specific business logic to calculate total revenue, ensuring financial accuracy even when plans change:
- **Confirmed Bookings:** 100% of the ticket price is added to revenue once the status is "Confirmed".
- **Cancellations:** In an emergency or change of plans, users can cancel their tickets for a 50% refund. The remaining **50% (Cancellation Fee)** is retained by the system and counted as revenue.

---

## 🛠️ Technology Stack

- **Framework:** Laravel 12.x
- **Frontend Interaction:** Livewire (v3.x) for dynamic UI components.
- **Database Engine:** Eloquent ORM (MySQL / SQLite).
- **Security Suite:** Laravel Jetstream & Laravel Sanctum.
- **Styling:** CSS3 & Tailwind CSS for a premium, responsive design.
