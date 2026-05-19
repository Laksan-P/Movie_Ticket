<button type="button"
    onclick="openCancellationPolicyModal(event)"
    {{ $attributes->merge(['class' => 'text-[#020617] font-bold underline hover:text-blue-900 transition-colors cursor-pointer bg-transparent border-none p-0 inline']) }}>
    {{ $slot->isEmpty() ? 'cancellation policy' : $slot }}
</button>
