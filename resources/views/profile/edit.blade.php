<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6]">
        <div class="w-full mx-auto max-w-4xl px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#020617] mb-1">Edit Profile</h1>
                <p class="text-sm text-slate-500">Update your personal information and profile picture</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-profile-nav active="edit" />

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                {{-- Prevent Cross-Site Request Forgery using CSRF token validation --}}
                @csrf
                @method('PUT')

                <div class="flex flex-col sm:flex-row items-center gap-6 mb-8 pb-8 border-b border-slate-100">
                    <div class="relative">
                        <x-profile-avatar :user="$user" size="lg" id="photo-preview" class="border-4 border-[#6482AD]/30 shadow-md" />
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-sm font-bold text-[#020617] mb-2">Profile Picture</p>
                        <p class="text-xs text-slate-500 mb-4">JPG, JPEG, PNG, or WEBP. Max 2 MB. Old photo is replaced automatically.</p>
                        <label for="photo"
                            class="inline-block px-5 py-2.5 rounded-xl bg-[#6482AD] text-white text-sm font-bold cursor-pointer hover:bg-[#006989] transition-all">
                            Choose Image
                        </label>
                        <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="hidden">
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-[#020617] mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-[#F6F6F6] border border-slate-200 text-[#020617] p-4 rounded-xl outline-none focus:border-[#6482AD] transition-all">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-bold text-[#020617] mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-[#F6F6F6] border border-slate-200 text-[#020617] p-4 rounded-xl outline-none focus:border-[#6482AD] transition-all">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-bold text-[#020617] mb-2">Phone Number <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full bg-[#F6F6F6] border border-slate-200 text-[#020617] p-4 rounded-xl outline-none focus:border-[#6482AD] transition-all"
                            placeholder="e.g. 0712345678">
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs text-slate-500"><strong class="text-[#020617]">Role:</strong> {{ ucfirst($user->role) }} — cannot be changed from profile settings.</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-[#0F4C75] text-white font-bold cursor-pointer hover:bg-black transition-all shadow-lg">
                        Save Changes
                    </button>
                    <a href="{{ route('profile.index') }}"
                        class="px-8 py-3 rounded-xl border-2 border-slate-200 text-[#020617] font-bold no-underline hover:bg-slate-50 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.getElementById('photo')?.addEventListener('change', function (e) {
            const file = e.target.files?.[0];
            if (!file) return;
            const preview = document.getElementById('photo-preview');
            preview.src = URL.createObjectURL(file);
        });
    </script>
</x-movie-layout>
