<?php

namespace App\Livewire;

use App\Models\Showtime;
use App\Models\Booking;
use Livewire\Component;

class SeatSelection extends Component
{
    public $showtime;
    public $selectedSeats = [];
    public $bookedSeats = [];

    public function mount(Showtime $showtime)
    {
        $this->showtime = $showtime;
        $this->loadBookedSeats();
    }

    public function loadBookedSeats()
    {
        $bookings = Booking::where('showtime_id', $this->showtime->id)
            ->where('status', 'confirmed')
            ->pluck('seats')
            ->toArray();

        $allBooked = [];
        foreach ($bookings as $seatString) {
            $allBooked = array_merge($allBooked, explode(',', $seatString));
        }
        $this->bookedSeats = $allBooked;
    }

    public function toggleSeat($seat)
    {
        if (in_array($seat, $this->bookedSeats)) return;

        if (in_array($seat, $this->selectedSeats)) {
            $this->selectedSeats = array_diff($this->selectedSeats, [$seat]);
        } else {
            $this->selectedSeats[] = $seat;
        }
    }

    public function render()
    {
        return view('livewire.seat-selection');
    }
}
