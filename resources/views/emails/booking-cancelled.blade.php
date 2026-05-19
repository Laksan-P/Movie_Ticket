@php
    $booking->loadMissing(['user', 'showtime.movie', 'showtime.theatre']);
    $showtimeAt = $booking->showtime?->showtime ? \Carbon\Carbon::parse($booking->showtime->showtime) : null;
@endphp

@component('emails.layout', ['title' => 'Booking Cancelled'])
    <h2 style="margin:0 0 8px;font-size:22px;color:#020617;">Hello, {{ $booking->user->name }}</h2>
    <p style="margin:0 0 24px;font-size:15px;color:#475569;line-height:1.6;">
        Your booking has been cancelled as requested. A partial refund will be processed according to our cancellation policy.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:24px;">
        <tr>
            <td style="padding:20px;">
                <p style="margin:0 0 12px;font-size:12px;font-weight:bold;color:#6482AD;text-transform:uppercase;letter-spacing:0.05em;">Cancellation Summary</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Movie:</strong> {{ $booking->showtime?->movie?->title ?? 'N/A' }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Theatre:</strong> {{ $booking->showtime?->theatre?->name ?? 'N/A' }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Date:</strong> {{ $showtimeAt ? $showtimeAt->format('M d, Y') : 'N/A' }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Time:</strong> {{ $showtimeAt ? $showtimeAt->format('h:i A') : 'N/A' }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Seats:</strong> {{ $booking->seats ?? 'N/A' }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Original amount:</strong> LKR {{ number_format($cancellation->original_amount, 2) }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Refund amount (50%):</strong> <span style="color:#15803d;font-weight:bold;">LKR {{ number_format($cancellation->refund_amount, 2) }}</span></p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Cancellation fee (50%):</strong> LKR {{ number_format($cancellation->cancellation_fee, 2) }}</p>
                <p style="margin:0;font-size:14px;"><strong>Status:</strong> <span style="color:#b91c1c;font-weight:bold;">{{ ucfirst($cancellation->status) }}</span></p>
            </td>
        </tr>
    </table>

    <p style="margin:0;font-size:14px;color:#475569;line-height:1.6;">
        Refunds are initiated immediately. Depending on your bank, it may take a few business days for the amount to appear on your statement.
    </p>
@endcomponent
