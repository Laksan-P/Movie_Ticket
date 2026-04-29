function switchTab(tab) {
    const activeContent = document.getElementById('active-content');
    const cancelledContent = document.getElementById('cancelled-content');
    const activeTabBtn = document.getElementById('active-tab');
    const cancelledTabBtn = document.getElementById('cancelled-tab');

    if (tab === 'active') {
        // Show active content, Hide cancelled content
        activeContent.classList.remove('hidden');
        cancelledContent.classList.add('hidden');

        // Update highlight: active tab on, cancelled tab off
        activeTabBtn.classList.add('tab-btn-active');
        cancelledTabBtn.classList.remove('tab-btn-active');
    } else {
        // Hide active content, Show cancelled content
        activeContent.classList.add('hidden');
        cancelledContent.classList.remove('hidden');

        // Update highlight: active tab off, cancelled tab on
        activeTabBtn.classList.remove('tab-btn-active');
        cancelledTabBtn.classList.add('tab-btn-active');
    }
}
