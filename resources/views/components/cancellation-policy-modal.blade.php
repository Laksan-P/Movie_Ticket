<div id="cancellation-policy-modal"
    class="hidden pointer-events-none fixed inset-0 z-[200] flex min-h-screen items-center justify-center px-4 py-8 bg-[#020617]/80 backdrop-blur-md"
    role="dialog"
    aria-modal="true"
    aria-labelledby="cancellation-policy-modal-title"
    aria-hidden="true">
    <div class="bg-white rounded-[2rem] p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-[0_20px_50px_rgba(0,0,0,0.3)]"
        onclick="event.stopPropagation()">
        <div class="flex items-start justify-between gap-4 mb-6">
            <h2 id="cancellation-policy-modal-title" class="text-2xl font-black text-[#020617]">Cancellation Policy</h2>
            <button type="button" onclick="closeCancellationPolicyModal()"
                class="shrink-0 p-2 rounded-lg text-slate-500 hover:bg-slate-100 border-none cursor-pointer bg-transparent"
                aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-5 text-sm text-slate-600 leading-relaxed">
            <div>
                <h3 class="font-bold text-[#020617] mb-1">Cancellation window</h3>
                <p>You may cancel up to <strong>30 minutes before</strong> the scheduled showtime. Cancellations are not allowed after the showtime or within 30 minutes of it starting.</p>
            </div>
            <div>
                <h3 class="font-bold text-[#020617] mb-1">Refund policy (50% refund)</h3>
                <p>Eligible cancellations receive a <strong>50% refund</strong> of the total ticket price. The remaining 50% is retained as a cancellation fee.</p>
            </div>
            <div>
                <h3 class="font-bold text-[#020617] mb-1">Processing</h3>
                <p>Refunds are initiated immediately after a successful cancellation. Your bank may take 3–5 business days to post the amount.</p>
            </div>
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs">
                Once confirmed, a cancellation cannot be undone and your seats are released for other customers.
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <button type="button" onclick="closeCancellationPolicyModal()"
                class="flex-1 py-3 px-6 rounded-xl border-2 border-[#6482AD] text-[#6482AD] font-bold bg-transparent cursor-pointer hover:bg-[#6482AD]/5">
                Close
            </button>
            <button type="button" onclick="closeCancellationPolicyModal()"
                class="flex-1 py-3 px-6 rounded-xl bg-[#6482AD] text-white font-bold border-none cursor-pointer hover:bg-[#006989]">
                I Understand
            </button>
        </div>
    </div>
</div>

@once
<script>
    (function () {
        if (window.__cancellationPolicyModalInit) return;
        window.__cancellationPolicyModalInit = true;

        window.openCancellationPolicyModal = function (event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const modal = document.getElementById('cancellation-policy-modal');
            if (!modal) return;
            modal.classList.remove('hidden', 'pointer-events-none');
            modal.classList.add('pointer-events-auto', 'flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        window.closeCancellationPolicyModal = function () {
            const modal = document.getElementById('cancellation-policy-modal');
            if (!modal) return;
            modal.classList.add('hidden', 'pointer-events-none');
            modal.classList.remove('pointer-events-auto', 'flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        };

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeCancellationPolicyModal();
            }
        });

        document.getElementById('cancellation-policy-modal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeCancellationPolicyModal();
            }
        });
    })();
</script>
@endonce
