@props([
    'user' => null,
    'size' => 'md',
    'class' => '',
])

@php
    $profileUser = $user ?? auth()->user();
    $sizeClasses = match ($size) {
        'sm' => 'w-9 h-9',
        'lg' => 'w-28 h-28',
        default => 'w-12 h-12',
    };
    $photoPath = $profileUser?->normalizedProfilePhotoPath();
    $avatarUrl = ($photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath))
        ? asset('storage/' . $photoPath)
        : ($profileUser?->profile_photo_url ?? '');
@endphp

@if ($profileUser)
    <img src="{{ $avatarUrl }}"
        alt="{{ $profileUser->name }}"
        {{ $attributes->merge(['class' => trim("$sizeClasses rounded-full object-cover $class")]) }}>
@endif
