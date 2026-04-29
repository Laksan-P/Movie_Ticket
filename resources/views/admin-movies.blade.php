<x-movie-layout>
<section class="min-h-screen py-12 px-4 bg-[#F6F6F6] text-[#020617]">
    <div class="w-full mx-auto max-w-6xl px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#6482AD] hover:text-[#006989] transition-colors no-underline mb-4 font-bold">
                    &larr; Back to Dashboard
                </a>
                <h1 class="text-4xl font-bold text-[#020617] mb-2 md:mb-1">Movie Management</h1>
                <p class="text-slate-500">View and manage movies in the system</p>
            </div>
            <button onclick="toggleForm()" class="w-full md:w-auto py-3 px-8 rounded-xl bg-[#6482AD] text-white font-bold transition-all hover:bg-[#006989] shadow-md active:scale-95 border-none cursor-pointer">
                + Add Movie
            </button>
        </div>

        <!-- Add/Edit Movie Form Area -->
        <div id="movie-form" class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-8 shadow-sm mb-8" style="display: none;">
            <h3 class="text-xl font-bold text-[#020617] mb-6" id="form-title">Add New Movie</h3>
            <form action="{{ route('admin.movies.store') }}" method="POST" id="actual-form">
                @csrf
                <div id="put-method"></div>
                <input type="hidden" name="movie_id" id="movie_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Movie Title</label>
                        <input type="text" name="title" id="title" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" placeholder="e.g. Inception">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Genre</label>
                        <input type="text" name="genre" id="genre" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" placeholder="e.g. Action, Sci-Fi">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Rating</label>
                        <select name="rating" id="rating" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium">
                            <option value="G">G - General</option>
                            <option value="PG">PG - Parental Guidance</option>
                            <option value="PG-13">PG-13 - Restricted Under 13</option>
                            <option value="R">R - Restricted</option>
                            <option value="NC-17">NC-17 - Adults Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Duration (min)</label>
                        <input type="number" name="duration" id="duration" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Release Date</label>
                        <input type="date" name="release_date" id="release_date" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Image URL</label>
                        <input type="text" name="image" id="image" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" placeholder="https://...">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Trailer URL</label>
                        <input type="text" name="trailer_url" id="trailer_url" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" placeholder="https://youtube.com/...">
                    </div>
                    <div class="md:col-span-2 flex items-center gap-3 bg-white/30 p-4 rounded-xl border border-black/5">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-5 h-5 accent-[#6482AD]">
                        <label for="is_active" class="text-sm font-bold text-[#020617] uppercase tracking-wide cursor-pointer">Set as Active Movie</label>
                    </div>
                </div>
                
                <div class="mb-10">
                    <label class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" placeholder="Movie synopsis..."></textarea>
                </div>

                <div class="flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 py-4 px-6 rounded-xl bg-[#6482AD] text-white font-bold transition-all hover:bg-[#006989] shadow-sm border-none cursor-pointer" id="form-submit-btn">
                        Save Movie
                    </button>
                    <button type="button" onclick="toggleForm()" class="flex-1 py-4 px-6 rounded-xl border-2 border-[#6482AD] text-[#6482AD] font-bold transition-all hover:bg-[#6482AD]/5 active:scale-95 cursor-pointer">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Movies Table -->
        <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-[#020617]/10">
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Movie Title</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Genre</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Rating</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Duration</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Release Date</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Status</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach ($movies as $movie)
                            <tr class="transition-colors hover:bg-black/5 group">
                                <td class="py-4 px-3">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ asset($movie->image) }}" alt="Poster" class="w-10 h-14 object-cover rounded shadow-sm bg-gray-200 border border-black/5">
                                        <div>
                                            <p class="text-[#020617] font-bold">{{ $movie->title }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-3 text-[#020617]/80 font-bold text-xs uppercase tracking-tight">{{ $movie->genre }}</td>
                                <td class="py-4 px-3 text-[#006989] font-extrabold">{{ $movie->rating }}</td>
                                <td class="py-4 px-3 text-[#020617] font-bold">{{ $movie->duration }} min</td>
                                <td class="py-4 px-3 text-[#020617]/60 font-bold text-[11px] uppercase">{{ \Carbon\Carbon::parse($movie->release_date)->format('M d, Y') }}</td>
                                <td class="py-4 px-3">
                                    @if($movie->is_active)
                                        <span class="inline-block px-3 py-1 rounded-md bg-green-700 text-white text-[10px] font-bold uppercase tracking-wider">Active</span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-md bg-red-700 text-white text-[10px] font-bold uppercase tracking-wider">Deactive</span>
                                    @endif
                                </td>
                                <td class="py-4 px-3">
                                    <div class="flex gap-2">
                                        @php
                                            $editData = $movie->only(['id','title','genre','rating','duration','release_date','image','trailer_url','description','is_active']);
                                        @endphp
                                        <button onclick='editMovie(@json($editData))' class="px-4 py-1.5 bg-[#6482AD] text-white rounded-md text-[10px] font-bold hover:bg-[#006989] transition-all border-none cursor-pointer">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-1.5 bg-red-600 text-white rounded-md text-[10px] font-bold hover:bg-red-700 transition-all border-none cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleForm() {
        const form = document.getElementById('movie-form');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            form.style.display = 'none';
            resetForm();
        }
    }

    function editMovie(movie) {
        const form = document.getElementById('movie-form');
        // Always show the form
        form.style.display = 'block';

        document.getElementById('form-title').innerText = 'Edit Movie: ' + movie.title;
        document.getElementById('movie_id').value = movie.id;
        document.getElementById('title').value = movie.title || '';
        document.getElementById('genre').value = movie.genre || '';
        document.getElementById('rating').value = movie.rating || '';
        document.getElementById('duration').value = movie.duration || '';
        document.getElementById('release_date').value = movie.release_date ? movie.release_date.split('T')[0] : '';
        document.getElementById('image').value = movie.image || '';
        document.getElementById('trailer_url').value = movie.trailer_url || '';
        document.getElementById('description').value = movie.description || '';
        document.getElementById('is_active').checked = (movie.is_active == 1 || movie.is_active === true);

        document.getElementById('put-method').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('actual-form').action = "/admin/movies/" + movie.id;
        document.getElementById('form-submit-btn').innerText = 'Update Movie';

        // Scroll to form
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function resetForm() {
        document.getElementById('actual-form').reset();
        document.getElementById('form-title').innerText = 'Add New Movie';
        document.getElementById('movie_id').value = '';
        document.getElementById('put-method').innerHTML = '';
        document.getElementById('actual-form').action = "{{ route('admin.movies.store') }}";
        document.getElementById('form-submit-btn').innerText = 'Add Movie';
        // Default: new movies are active
        document.getElementById('is_active').checked = true;
    }
</script>
</x-movie-layout>
