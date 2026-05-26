<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // Prevent unauthorized profile modifications — always use authenticated user
        $user = $request->user()->fresh();

        return view('profile.index', compact('user'));
    }

    public function edit(Request $request)
    {
        $user = $request->user()->fresh();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Prevent unauthorized profile modifications
        $user = $request->user();

        // Validate incoming request data before processing
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            // Validate uploaded image type and size for security
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Sanitize user-generated input to prevent XSS attacks
        $cleanName = strip_tags($validated['name']);
        $cleanPhone = isset($validated['phone']) ? strip_tags($validated['phone']) : null;

        // Prevent mass assignment vulnerabilities — only allow safe profile fields (role unchanged)
        $user->forceFill([
            'name' => $cleanName,
            'email' => $validated['email'],
            'phone' => $cleanPhone,
        ]);

        // Synchronize updated profile information with MySQL database
        $user->save();

        if ($request->hasFile('photo')) {
            $this->storeProfilePhoto($user, $request->file('photo'));
        }

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function security(Request $request)
    {
        $user = $request->user()->fresh();

        return view('profile.security', compact('user'));
    }

    /**
     * Securely store uploaded profile images using Laravel Storage.
     */
    protected function storeProfilePhoto($user, $uploadedFile): void
    {
        // Prevent malicious executable file uploads — images only (validated via mimes rule)
        Storage::disk('public')->makeDirectory('profile-photos');

        $previousPath = $user->normalizedProfilePhotoPath();

        // Securely store uploaded avatars in storage/app/public/profile-photos
        $path = User::normalizeProfilePhotoPath($uploadedFile->store('profile-photos', 'public'));

        if (! $path) {
            return;
        }

        $user->forceFill([
            'profile_photo_path' => $path,
        ])->save();

        if ($previousPath && $previousPath !== $path && Storage::disk('public')->exists($previousPath)) {
            Storage::disk('public')->delete($previousPath);
        }
    }
}
