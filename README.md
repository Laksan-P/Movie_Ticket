# MovieBuff — Movie Booking System

MovieBuff is a Laravel 12 web application developed for the Server Side Programming 2 assignment. The system allows customers to browse movies, select seats, book tickets, complete payments, cancel bookings under a refund policy, and manage their personal bookings. It also includes an admin dashboard for managing movies, theatres, showtimes, bookings, and cancellations.

The project uses Laravel Jetstream for web authentication, Laravel Sanctum for API authentication, MySQL as the database, Eloquent ORM for database interaction, Livewire for the seat selection interface, SMTP for transactional emails, and Stripe Checkout in test mode as an external payment gateway integration.

---

## Project Links

Hosted application:  
http://moviebuff.infinityfreeapp.com/

GitHub repository:  
https://github.com/Laksan-P/Movie_Ticket

Postman collection:  
[text](docs/moviebuff-api-postman-collection.json)

SQL database dump:  
movie_ticket.sql

---

## Main Features

MovieBuff provides a complete movie ticket booking workflow. Customers can register, log in, browse available movies, choose showtimes, select seats, and create bookings. Bookings are initially created as pending and can later be confirmed through either the demo payment flow or Stripe Checkout in test mode.

The system prevents already selected seats from being booked again. Seat availability is checked on the backend, so the booking logic does not depend only on frontend display. This helps protect the application from invalid or duplicated seat reservations.

Customers can view their bookings under active, past, and cancelled categories. Eligible bookings can be cancelled based on the cancellation policy, where 50% of the ticket amount is refunded and the remaining 50% is retained as a cancellation fee.

The admin panel allows authorised admin users to manage movies, theatres, and showtimes. Admin users can also review bookings and cancellations for operational monitoring.

---

## Technology Stack

The backend is developed using Laravel 12 and MySQL. Laravel Jetstream, Fortify, and Sanctum are used for authentication and API security. Eloquent ORM is used for database operations, and Blade with Tailwind CSS is used for the user interface. Livewire is used for the interactive seat selection page. Vite is used for frontend asset compilation.

The payment module includes a demo payment flow and an optional Stripe Checkout test integration. Email notifications are handled using Laravel Mail with SMTP support.

---

## Local Installation Guide

Before running the project locally, make sure PHP 8.2 or higher, Composer, Node.js, npm, and MySQL are installed.

Clone or extract the project and open the project folder in a terminal.

```bash
composer install
npm install
npm run build