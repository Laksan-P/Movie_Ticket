# Movie Booking System

A robust and secure web-based movie booking application built with Laravel 12. This system provides a seamless experience for moviegoers to browse shows, select seats, and manage bookings, while giving administrators a dashboard to manage movies, theatres, and showtimes.

## Core Features

### User Management
- **Secure Authentication:** Laravel Jetstream for registration, login, and profile management.
- **My Bookings:** Users can view booking history, confirm mock payments, and cancel eligible bookings.
- **Interactive Seat Selection:** Livewire-powered seat grid with real-time selection (no full page reload).

### Admin Dashboard
- **Movie Management:** CRUD for movies, posters, and active status.
- **Theatre & Showtime Management:** Register theatres and deploy showtime sessions.
- **Booking Oversight:** View customer bookings and cancellations.
- **Revenue Tracking:** Confirmed sales plus 50% cancellation fees count toward revenue.

---

## Security Implementation

- **Laravel Jetstream authentication:** Web users register and log in through Jetstream/Fortify with session-based access to protected routes.
- **Password hashing:** Passwords are hashed with Bcrypt before storage (never stored in plain text).
- **CSRF protection:** All state-changing web forms include CSRF tokens; the API layer uses Sanctum stateful domains where applicable.
- **Sanctum token-based API authentication:** Mobile or API clients authenticate with `Authorization: Bearer {token}` after login or register.
- **Role-based admin middleware:** The `admin` middleware restricts `/admin/*` routes to users with the `admin` role.
- **Input validation:** Controllers validate requests with Laravel validation rules before database writes.
- **Eloquent ORM / SQL injection protection:** Queries use Eloquent and parameter binding instead of raw user input.
- **Booking ownership checks:** Payment, cancellation, and related API actions verify the booking belongs to the authenticated user (admins may access all).
- **No CVV storage / secure mock payment handling:** CVV is collected only for demo UI validation; payments store method, card last four digits, transaction id, and status only.
- **Route protection:** Customer booking routes use `auth:sanctum`, Jetstream session, and `verified` middleware.

---

## API Endpoints

Base URL: `/api`

| Method | Endpoint | Auth (Bearer token) | Description |
|--------|----------|-------------------|-------------|
| POST | `/register` | No | Register a user; returns Sanctum token and user JSON (201). |
| POST | `/login` | No | Login; returns Sanctum token and user JSON (200). Optional `device_name` (defaults to `api-token`). |
| POST | `/logout` | Yes | Revoke current token and end session (200). |
| GET | `/movies` | No | List active movies (200). |
| GET | `/movies/{id}` | No | View a single movie with showtimes (200/404). |
| GET | `/user` | Yes | Authenticated user profile (200). |
| GET | `/bookings` | Yes | List authenticated user's bookings (200). |
| POST | `/bookings` | Yes | Create booking with `showtime_id`, `seats`, `number_of_tickets` (201/422). |
| POST | `/bookings/{booking}/confirm` | Yes | Confirm mock payment (200/403/422). |
| POST | `/bookings/{booking}/cancel` | Yes | Cancel booking with `reason`, optional `comments` (200/403/422). |

**Authentication header (protected routes):**

```
Authorization: Bearer {your-sanctum-token}
Accept: application/json
```

**Example register response:**

```json
{
  "message": "User registered successfully",
  "token": "1|...",
  "user": { "id": 1, "name": "...", "email": "..." }
}
```

**Error responses:** `401` unauthenticated, `403` forbidden, `404` not found, `422` validation errors (with `errors` object).

---

## Demo Testing Flow

1. **Register** a new user at `/register` (web) or `POST /api/register`.
2. **Login** at `/login` or `POST /api/login` and note the API token if testing the API.
3. **Browse movies** on the home page or via `GET /api/movies`.
4. **Select a showtime** from a theatre and open the seat selection page.
5. **Select seats** using the Livewire seat grid and proceed to payment.
6. **Create booking** (pending status) and complete **mock payment** on the payment page.
7. **View My Bookings** at `/my-bookings`.
8. **Cancel a booking** from My Bookings (50% refund policy); confirm cancellation cannot be repeated.
9. **Admin login** (user with `role = admin`) and manage movies, theatres, and showtimes at `/admin/dashboard`.

---

## Business Logic: Revenue Calculation

- **Confirmed bookings:** 100% of ticket price counts as revenue.
- **Cancellations:** Users receive a 50% refund; the remaining 50% cancellation fee is retained as revenue.

---

## Technology Stack

- **Framework:** Laravel 12.x
- **Frontend interaction:** Livewire 3.x (seat selection)
- **Database:** Eloquent ORM (MySQL / SQLite)
- **Security:** Laravel Jetstream & Laravel Sanctum
- **Styling:** Tailwind CSS
