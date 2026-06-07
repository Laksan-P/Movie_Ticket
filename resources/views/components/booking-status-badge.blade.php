@props([
    'status',
    'admin' => false,
    'soft' => false,
])

@php
    use App\Support\BookingStatus;

    $classes = $soft
        ? BookingStatus::badgeClassesSoft($status)
        : BookingStatus::badgeClasses($status);
    $label = BookingStatus::label($status, $admin);
@endphp

<span {{ $attributes->merge(['class' => "inline-block px-3 py-1 rounded-md text-[10px] font-bold {$classes}"]) }}>
    {{ $label }}
</span>
