function switchTab(tab) {
    const pendingContent = document.getElementById('pending-content');
    const allContent = document.getElementById('all-content');
    const pendingTabBtn = document.getElementById('pending-tab');
    const allTabBtn = document.getElementById('all-tab');

    if (tab === 'pending') {
        // Pending Content
        pendingContent.classList.remove('hidden');
        allContent.classList.add('hidden');

        // Pending Tab Active
        pendingTabBtn.classList.add('border-[#6482AD]', 'text-[#6482AD]');
        pendingTabBtn.classList.remove('border-transparent', 'text-[#020617]/50');

        // All Tab Inactive
        allTabBtn.classList.add('border-transparent', 'text-[#020617]/50');
        allTabBtn.classList.remove('border-[#6482AD]', 'text-[#6482AD]');
    } else {
        // All Content
        pendingContent.classList.add('hidden');
        allContent.classList.remove('hidden');

        // All Tab Active
        allTabBtn.classList.add('border-[#6482AD]', 'text-[#6482AD]');
        allTabBtn.classList.remove('border-transparent', 'text-[#020617]/50');

        // Pending Tab Inactive
        pendingTabBtn.classList.add('border-transparent', 'text-[#020617]/50');
        pendingTabBtn.classList.remove('border-[#6482AD]', 'text-[#6482AD]');
    }
}
