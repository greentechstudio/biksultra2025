<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is admin - redirect to admin dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // For regular users, show their profile/status
        return $this->userDashboard();
    }

    private function userDashboard()
    {
        $user = Auth::user();
        
        // User hanya bisa melihat status pendaftaran mereka sendiri
        $userStatus = [
            'name' => $user->name,
            'email' => $user->email,
            'race_category' => $user->race_category,
            'jersey_size' => $user->jersey_size,
            'whatsapp_verified' => $user->whatsapp_verified,
            'whatsapp_verified_at' => $user->whatsapp_verified_at,
            'payment_confirmed' => $user->payment_confirmed,
            'payment_confirmed_at' => $user->payment_confirmed_at,
            'payment_amount' => $user->payment_amount,
            'status' => $user->status,
            'can_edit_profile' => $user->canEditProfile(),
            'profile_edited_at' => $user->profile_edited_at,
        ];

        // Get category info and price
        $category = RaceCategory::where('name', $user->race_category)->first();
        $categoryInfo = $category ? [
            'name' => $category->name,
            'description' => $category->description,
            'price' => $category->price,
        ] : null;

        return view('dashboard.user', compact('user', 'userStatus', 'categoryInfo'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
