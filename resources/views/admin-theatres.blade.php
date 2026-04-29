<x-movie-layout>
<script>
    function toggleForm() {
        const form = document.getElementById('theatre-form');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }

    function editTheatre(theatre) {
        document.getElementById('theatre-form').style.display = 'block';
        document.getElementById('form-title').innerText = 'Edit Theatre';
        document.getElementById('theatre_id').value = theatre.id;
        document.getElementById('name').value = theatre.name;
        document.getElementById('location').value = theatre.location;
        document.getElementById('total_seats').value = theatre.total_seats;
        document.getElementById('description').value = theatre.description;
        document.getElementById('is_active').checked = !!theatre.is_active;
        
        document.getElementById('form-method').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('actual-form').action = "/admin/theatres/" + theatre.id;
    }
</script>

<section class="min-h-screen py-12 px-4 bg-[#F6F6F6] text-[#020617]">
    <div class="w-full mx-auto max-w-6xl px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#6482AD] hover:text-[#006989] transition-colors no-underline mb-4 font-bold">
                    ← Back to Dashboard
                </a>
                <h1 class="text-4xl font-bold text-[#020617] mb-2 md:mb-1">Theatre Management</h1>
                <p class="text-slate-500">Create, update, and manage theatres</p>
            </div>
            <button onclick="toggleForm()" class="w-full md:w-auto py-3 px-8 rounded-xl bg-[#6482AD] text-white font-bold transition-all hover:bg-[#006989] shadow-md active:scale-95 border-none cursor-pointer">
                + Add Theatre
            </button>
        </div>

        <!-- Add/Edit Form -->
        <div id="theatre-form" class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-8 shadow-sm mb-8" style="display: none;">
            <h3 class="text-xl font-bold text-[#020617] mb-6" id="form-title">Add New Theatre</h3>
            <form method="POST" action="{{ route('admin.theatres.store') }}" id="actual-form">
                @csrf
                <div id="form-method"></div>
                <input type="hidden" name="theatre_id" id="theatre_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Theatre Name</label>
                        <input type="text" id="name" name="name" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium">
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Location</label>
                        <input type="text" id="location" name="location" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium">
                    </div>
                    <div>
                        <label for="total_seats" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Total Seats</label>
                        <input type="number" id="total_seats" name="total_seats" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" min="1">
                    </div>
                </div>
                <div class="mb-8 flex items-center gap-3 bg-white/50 p-4 rounded-xl border border-gray-200">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="w-5 h-5 accent-[#6482AD] cursor-pointer">
                    <label for="is_active" class="text-sm font-bold text-[#020617] cursor-pointer">Set as Active Theatre</label>
                </div>
                <div class="mb-8">
                    <label for="description" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Description</label>
                    <textarea id="description" name="description" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" rows="3"></textarea>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 py-4 px-6 rounded-xl bg-[#6482AD] text-white font-bold transition-all hover:bg-[#006989] shadow-sm border-none cursor-pointer">
                        Save Theatre
                    </button>
                    <button type="button" onclick="toggleForm()" class="flex-1 py-4 px-6 rounded-xl border-2 border-[#6482AD] text-[#6482AD] bg-transparent font-bold transition-all hover:bg-[#6482AD]/5 active:scale-95 cursor-pointer">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-600 rounded-lg p-4 mb-8">
                <p class="text-sm text-green-700 font-bold m-0">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Theatres Table -->
        <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <!-- Desktop Table -->
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-[#020617]/10">
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Theatre Name</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Location</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Capacity</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Status</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach ($theatres as $theatre)
                            <tr class="transition-colors hover:bg-black/5 group">
                                <td class="py-4 px-3">
                                    <p class="text-[#020617] font-bold">{{ $theatre->name }}</p>
                                </td>
                                <td class="py-4 px-3 text-[#020617]/80 font-medium">{{ $theatre->location }}</td>
                                <td class="py-4 px-3 text-[#020617] font-bold">{{ $theatre->total_seats }} seats</td>
                                <td class="py-4 px-3">
                                    @if($theatre->is_active)
                                        <span class="inline-block px-3 py-1 rounded-md bg-green-700 text-white text-[10px] font-bold">Active</span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-md bg-red-700 text-white text-[10px] font-bold uppercase">Deactive</span>
                                    @endif
                                </td>
                                <td class="py-4 px-3">
                                    <div class="flex gap-2">
                                        <button onclick='editTheatre({!! json_encode($theatre) !!})' class="px-4 py-1.5 bg-[#6482AD] text-white rounded-md text-[10px] font-bold hover:bg-[#006989] transition-all border-none cursor-pointer shadow-sm">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.theatres.destroy', $theatre->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-1.5 bg-red-600 text-white rounded-md text-[10px] font-bold hover:bg-red-700 transition-all border-none cursor-pointer shadow-sm">
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
</x-movie-layout>
