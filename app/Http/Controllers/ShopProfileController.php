<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopProfileController extends Controller
{
    public function index()
    {
        return view('customer.profile.account');
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('shop.login')->with('error', 'Please login to update your profile');
        }

        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'gender' => 'nullable|in:male,female,other,Male,Female',
            'bday' => 'nullable|date',
            'mobileno' => 'nullable|string|max:20',
            'hnum' => 'nullable|string|max:255',
            'brgy' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'zcode' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($customer->avatar) {
                Storage::disk('public')->delete($customer->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        try {
            $customer->update($validated);

            return redirect()->route('profile.auth.account')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('profile.auth.account')->with('error', 'Failed to update profile. Please try again.');
        }
    }
}
