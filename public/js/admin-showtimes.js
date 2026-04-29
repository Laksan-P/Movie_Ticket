function toggleForm() {
    const form = document.getElementById('showtime-form');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';

        // Reset form if cancelling adding new showtime
        if (!location.search.includes('action=edit')) {
            form.querySelector('form').reset();
        }
    }
}
