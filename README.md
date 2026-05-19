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

Base URL: `{{APP_URL}}/api` (e.g. `http://localhost:8000/api`)

All API responses use JSON with a consistent shape:

- **Success:** `{ "message": "...", "data": { ... } }` (HTTP `200` or `201`)
- **Error:** `{ "message": "...", "errors": { ... } }` (optional `errors` on validation failures)

### Authentication

Protected routes require:

```
Authorization: Bearer {your-sanctum-token}
Accept: application/json
Content-Type: application/json
```

### Endpoint reference

| Method | Endpoint | Auth required? | Description | Example request body |
|--------|----------|----------------|-------------|----------------------|
| POST | `/register` | No | Register a new user; returns token and user in `data` | `{ "name", "email", "password", "password_confirmation", "device_name?" }` |
| POST | `/login` | No | Login; returns token and user in `data` | `{ "email", "password", "device_name?" }` |
| POST | `/logout` | Yes | Revoke current API token | — |
| GET | `/user` | Yes | Get authenticated user profile | — |
| GET | `/movies` | No | List all active movies | — |
| GET | `/movies/{id}` | No | View one movie with upcoming showtimes | — |
| GET | `/bookings` | Yes | List current user's bookings | — |
| POST | `/bookings` | Yes | Create a pending booking | `{ "showtime_id", "seats": "A1,A2", "number_of_tickets" }` |
| POST | `/bookings/{booking}/confirm` | Yes | Confirm mock payment | `{ "payment_method?", "card_number?" }` |
| POST | `/bookings/{booking}/cancel` | Yes | Cancel booking (50% refund) | `{ "reason", "comments?" }` |

### HTTP status codes

| Code | Meaning |
|------|---------|
| `200` | Success |
| `201` | Created (register, create booking) |
| `401` | Unauthenticated (missing/invalid token) |
| `403` | Forbidden (e.g. another user's booking) |
| `404` | Resource or endpoint not found |
| `422` | Validation error (`errors` object included) |

### Example success response (login)

```json
{
  "message": "Login successful.",
  "data": {
    "token": "1|plainTextToken...",
    "user": {
      "id": 1,
      "name": "API Tester",
      "email": "api.tester@example.com"
    }
  }
}
```

### Example error response (validation)

```json
{
  "message": "Validation failed.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Postman collection

Import `docs/moviebuff-api-postman-collection.json` into Postman. Collection variables:

- `{{base_url}}` — e.g. `http://localhost:8000/api`
- `{{token}}` — set automatically after Register/Login
- `{{movie_id}}`, `{{showtime_id}}`, `{{booking_id}}` — adjust per your database

---

## API Testing Flow

1. **Register** — `POST /api/register` with name, email, password, and `password_confirmation`. Copy `data.token` from the response (or use the Postman test script).
2. **Login** (optional) — `POST /api/login` if you already have an account; copy the Bearer token.
3. **Set Authorization** — In Postman or your client, add header `Authorization: Bearer {token}` for protected routes.
4. **Get user** — `GET /api/user` to verify the token works.
5. **List movies** — `GET /api/movies` (public).
6. **View movie** — `GET /api/movies/{id}` to see showtimes; note a `showtime_id`.
7. **Create booking** — `POST /api/bookings` with `showtime_id`, comma-separated `seats`, and `number_of_tickets`. Save `data.booking.id`.
8. **Confirm payment** — `POST /api/bookings/{booking}/confirm` with optional mock card fields.
9. **Cancel booking** — `POST /api/bookings/{booking}/cancel` with `reason` and optional `comments`.
10. **Logout** — `POST /api/logout` to revoke the token.

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

## Email Notifications (SMTP)

The application sends transactional emails when a booking is **confirmed** (after successful mock payment) and when a booking is **cancelled** (with refund details). Emails are sent once per successful action; repeated confirm/cancel requests do not trigger duplicate messages.

### Configure `.env`

Use your SMTP provider credentials. **Do not commit real passwords** to version control.

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@moviebuff.test
MAIL_FROM_NAME="MovieBuff"
```

For local development without SMTP, you can use:

```env
MAIL_MAILER=log
```

Emails will be written to `storage/logs/laravel.log` instead of being sent.

### Testing emails

1. Set `MAIL_MAILER=log` or valid SMTP settings in `.env`.
2. Run `php artisan config:clear` after changing mail settings.
3. Complete a **mock payment** on a pending booking — check inbox or `storage/logs/laravel.log`.
4. **Cancel** a confirmed booking — verify the cancellation/refund email.
5. Retry confirm/cancel on the same booking — no duplicate emails should be sent.

### Mailable classes

- `App\Mail\BookingConfirmedMail` — triggered from `BookingService::confirmPayment()` on first successful confirmation.
- `App\Mail\BookingCancelledMail` — triggered from `BookingService::cancelBooking()` on first successful cancellation.

---

## Technology Stack

- **Framework:** Laravel 12.x
- **Frontend interaction:** Livewire 3.x (seat selection)
- **Database:** Eloquent ORM (MySQL / SQLite)
- **Security:** Laravel Jetstream & Laravel Sanctum
- **Styling:** Tailwind CSS
