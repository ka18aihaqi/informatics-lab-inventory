<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('profile.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Simpan user baru
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Redirect ke halaman tertentu setelah registrasi berhasil
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
    
        try {
            $validated = $request->validate([
                'username' => "required|string|unique:users,username,{$user->id}",
                'email' => "required|email|unique:users,email,{$user->id}",
            ], [
                'username.unique' => '<strong>Username</strong> has been taken.',
                'email.unique' => '<strong>Email</strong> has been taken.',
            ]);
    
            $user->update($validated);
    
            return back()->with('success', '<strong>Updated successfully.</strong><br>Profile has been successfully updated.');
        } catch (ValidationException $e) {
            $messages = implode('<br>', $e->validator->errors()->all());
            return back()
                ->withErrors($e->validator) // biar bisa tetap pakai $errors di blade
                ->with('error', '<strong>Update Failed.</strong><br>' . $messages);
        }
    }

    /**
     * Password update for a specified resource in storage.
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ], [
                'old_password.required' => '<strong>Current Password</strong> is required.',
                'new_password.required' => '<strong>New Password</strong> is required.',
                'new_password.min' => '<strong>New Password</strong> must be at least 6 characters.',
                'new_password.confirmed' => '<strong>New Password</strong> confirmation does not match.',
            ]);
    
            $user = Auth::user();
    
            if (!Hash::check($validated['old_password'], $user->password)) {
                return back()->with('error', '<strong>Update Failed.</strong><br>Current password is incorrect.');
            }
    
            $user->update(['password' => Hash::make($validated['new_password'])]);
    
            return back()->with('success', '<strong>Updated successfully.</strong><br>Password has been successfully updated.');
        } catch (ValidationException $e) {
            $messages = implode('<br>', $e->validator->errors()->all());
            return back()
                ->withErrors($e->validator)
                ->with('error', '<strong>Update Failed.</strong><br>' . $messages);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        Auth::logout();
        return redirect()->route('login')->with('success', 'Your account has been deleted.');
    }

    /**
     * Returns the specified resource from storage.
     */
    public function restore()
    {
        $user = User::onlyTrashed()->find(Auth::id());

        if (!$user) {
            return redirect()->back()->withErrors(['message' => 'User not found.']);
        }

        $user->restore();
        return redirect()->back()->with('success', 'User restored successfully.');
    }

    /**
     * Permanently remove the specified resource from storage.
     */
    public function forceDelete()
    {
        $user = User::onlyTrashed()->find(Auth::id());

        if (!$user) {
            return redirect()->back()->withErrors(['message' => 'User not found.']);
        }

        $user->forceDelete();
        return redirect()->route('login')->with('success', 'User permanently deleted.');
    }
}
