<?php

namespace App\Livewire;

use App\Models\Showtime;
use App\Services\BookingService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SeatSelection extends Component
{
    public Showtime $showtime;

    public array $selectedSeats = [];

    public array $bookedSeats = [];

    public function mount(Showtime $showtime): void
    {
        $this->showtime = $showtime->load(['movie', 'theatre']);
        $this->loadBookedSeats();
    }

    public function loadBookedSeats(): void
    {
        $this->bookedSeats = app(BookingService::class)
            ->getUnavailableSeats($this->showtime->id);
    }

    public function toggleSeat(string $seat): void
    {
        if (in_array($seat, $this->bookedSeats, true)) {
            return;
        }

        if (in_array($seat, $this->selectedSeats, true)) {
            $this->selectedSeats = array_values(array_diff($this->selectedSeats, [$seat]));
        } else {
            $this->selectedSeats[] = $seat;
        }
    }

    public function proceedToPayment(): void
    {
        if ($this->selectedSeats === []) {
            $this->addError('seats', 'Please select at least one seat.');

            return;
        }

        try {
            $booking = app(BookingService::class)->createBooking(
                auth()->id(),
                $this->showtime->id,
                implode(',', $this->selectedSeats),
                count($this->selectedSeats)
            );
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? 'Booking failed.';
            $this->addError('seats', $message);

            return;
        }

        $this->redirect(route('bookings.payment', $booking->id), navigate: false);
    }

    public function render()
    {
        return view('livewire.seat-selection');
    }
}
