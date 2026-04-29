function toggleForm() {
    const form = document.getElementById('movie-form');
    if (form.style.display === 'none' || form.style.display === '') {
        resetForm(); // Reset when opening 'Add' mode
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth' });
    } else {
        form.style.display = 'none';
    }
}

function resetForm() {
    document.getElementById('actual-form').reset();
    document.getElementById('form-title').textContent = 'Add New Movie';
    const actionInput = document.getElementById('form-action-input');
    actionInput.name = 'create_movie';
    actionInput.value = 'true';
    document.getElementById('form-submit-btn').textContent = 'Add Movie';
    document.getElementById('movie_id').value = '';
}

function editMovie(movie) {
    const form = document.getElementById('movie-form');
    form.style.display = 'block';

    // Update Mode
    document.getElementById('form-title').textContent = 'Update Movie';
    const actionInput = document.getElementById('form-action-input');
    actionInput.name = 'update_movie';
    actionInput.value = 'true';
    document.getElementById('form-submit-btn').textContent = 'Update';
    document.getElementById('movie_id').value = movie.id;

    // Fill Fields
    const f = document.forms['actual-form'];
    f['title'].value = movie.title || '';
    f['genre'].value = movie.genre || '';
    f['rating'].value = movie.rating || 'G';
    f['duration'].value = movie.duration || '';
    f['release_date'].value = movie.release_date || '';
    f['image'].value = movie.image || '';
    f['trailer_url'].value = movie.trailer_url || '';
    f['description'].value = movie.description || '';

    // Handle Formats (Checkbox)
    const formatStr = movie.formats || '';
    const formats = formatStr.split(',').map(s => s.trim());
    const formatCheckboxes = document.querySelectorAll('input[name="formats[]"]');
    formatCheckboxes.forEach(cb => {
        cb.checked = formats.includes(cb.value);
    });

    // Handle Languages (Checkbox)
    const langStr = movie.languages || '';
    const langs = langStr.split(',').map(s => s.trim());
    const langCheckboxes = document.querySelectorAll('input[name="languages[]"]');
    langCheckboxes.forEach(cb => {
        cb.checked = langs.includes(cb.value);
    });

    form.scrollIntoView({ behavior: 'smooth' });
}
