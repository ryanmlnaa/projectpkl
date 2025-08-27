<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }
    
    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB'
        ]);
        
        // Update nama dan email
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Handle upload foto profile
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            // Upload foto baru
            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');
            
            // Simpan path ke database
            $user->profile_photo_path = $path;
        }
        
        // Simpan perubahan
        $user->save();
        
        return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
    }
    
    /**
     * Delete profile photo
     */
    public function deletePhoto()
    {
        $user = Auth::user();
        
        if ($user->profile_photo_path) {
            // Hapus file
            Storage::disk('public')->delete($user->profile_photo_path);
            
            // Update database
            $user->profile_photo_path = null;
            $user->save();
        }
        
        return redirect()->back()->with('success', 'Foto profile berhasil dihapus!');
    }
}