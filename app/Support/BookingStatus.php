<?php

namespace App\Support;

class BookingStatus
{
    public const PENDING = 'pending';

    public const CONFIRMED = 'confirmed';

    public const CANCELLATION_REQUESTED = 'cancellation_requested';

    public const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::CANCELLATION_REQUESTED,
            self::CANCELLED,
        ];
    }

    public static function label(string $status, bool $admin = false): string
    {
        return match ($status) {
            self::PENDING => $admin ? 'Pending' : 'Pending Payment',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLATION_REQUESTED => $admin
                ? 'Pending Cancellation Request'
                : 'Cancellation Requested',
            self::CANCELLED => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    /** Tailwind classes for pill badges (table/admin). */
    public static function badgeClasses(string $status): string
    {
        return match ($status) {
            self::PENDING => 'bg-amber-600 text-white',
            self::CONFIRMED => 'bg-green-700 text-white',
            self::CANCELLATION_REQUESTED => 'bg-orange-600 text-white',
            self::CANCELLED => 'bg-red-700 text-white',
            default => 'bg-slate-600 text-white',
        };
    }

    /** Softer badge variant for dashboard cards. */
    public static function badgeClassesSoft(string $status): string
    {
        return match ($status) {
            self::PENDING => 'bg-amber-100 text-amber-800',
            self::CONFIRMED => 'bg-green-100 text-green-700',
            self::CANCELLATION_REQUESTED => 'bg-orange-100 text-orange-800',
            self::CANCELLED => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    public static function textColor(string $status): string
    {
        return match ($status) {
            self::PENDING => 'text-amber-700',
            self::CONFIRMED => 'text-green-700',
            self::CANCELLATION_REQUESTED => 'text-orange-700',
            self::CANCELLED => 'text-red-700',
            default => 'text-slate-600',
        };
    }

    public static function dotColor(string $status): string
    {
        return match ($status) {
            self::PENDING => 'bg-amber-500',
            self::CONFIRMED => 'bg-green-500',
            self::CANCELLATION_REQUESTED => 'bg-orange-500',
            self::CANCELLED => 'bg-red-500',
            default => 'bg-slate-400',
        };
    }
}
