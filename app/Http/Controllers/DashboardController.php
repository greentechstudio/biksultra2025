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
        
        // Check if user is admin or regular user
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard()
    {
        $user = Auth::user();
        
        // Basic stats (exclude admin users)
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'verified_users' => User::where('role', '!=', 'admin')->where('whatsapp_verified', true)->count(),
            'paid_users' => User::where('role', '!=', 'admin')->where('payment_confirmed', true)->count(),
            'pending_users' => User::where('role', '!=', 'admin')->where('payment_confirmed', false)->count(),
        ];

        // Get all race categories with participant counts and revenue (exclude admin users)
        $categoryStats = RaceCategory::all()->map(function($category) {
            // Count participants per category (exclude admin users)
            $totalParticipants = User::where('race_category', $category->name)
                                    ->where('role', '!=', 'admin')->count();
            $confirmedParticipants = User::where('race_category', $category->name)
                                       ->where('role', '!=', 'admin')
                                       ->where('payment_confirmed', true)->count();
            $pendingParticipants = $totalParticipants - $confirmedParticipants;
            
            // Calculate revenue
            $confirmedRevenue = $confirmedParticipants * $category->price;
            $pendingRevenue = $pendingParticipants * $category->price;
            $totalRevenue = $totalParticipants * $category->price;
            
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'price' => $category->price,
                'total_participants' => $totalParticipants,
                'confirmed_participants' => $confirmedParticipants,
                'pending_participants' => $pendingParticipants,
                'confirmed_revenue' => $confirmedRevenue,
                'pending_revenue' => $pendingRevenue,
                'total_revenue' => $totalRevenue,
                'active' => $category->active,
            ];
        });

        // Calculate total revenue across all categories
        $totalRevenue = [
            'confirmed' => $categoryStats->sum('confirmed_revenue'),
            'pending' => $categoryStats->sum('pending_revenue'),
            'total' => $categoryStats->sum('total_revenue'),
        ];

        // Recent registrations (exclude admin users)
        $recentUsers = User::where('role', '!=', 'admin')->latest()->limit(5)->get();

        return view('dashboard.index', compact('user', 'stats', 'categoryStats', 'totalRevenue', 'recentUsers'));
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

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->latest()->paginate(10);
        return view('dashboard.users', compact('users'));
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

    public function whatsappVerification()
    {
        $user = Auth::user();
        
        if ($user->whatsapp_verified) {
            return redirect('/dashboard')->with('info', 'WhatsApp already verified!');
        }

        $whatsappMessage = urlencode("Halo {$user->name}!\n\nSilakan konfirmasi verifikasi WhatsApp Anda dengan membalas: VERIFY-{$user->id}\n\nTerima kasih!");
        
        $whatsappUrl = config('app.whatsapp_api_url') . '?phone=' . config('app.whatsapp_business_number') . '&text=' . $whatsappMessage;

        return view('dashboard.whatsapp-verification', [
            'user' => $user,
            'whatsappUrl' => $whatsappUrl
        ]);
    }

    public function paymentConfirmation()
    {
        $user = Auth::user();
        
        if ($user->payment_confirmed) {
            return redirect('/dashboard')->with('info', 'Payment already confirmed!');
        }

        $whatsappMessage = urlencode("Halo {$user->name}!\n\nSilakan konfirmasi pembayaran Anda dengan mengirim bukti transfer dan membalas: PAYMENT-{$user->id}-100000\n\nTerima kasih!");
        
        $whatsappUrl = config('app.whatsapp_api_url') . '?phone=' . config('app.whatsapp_business_number') . '&text=' . $whatsappMessage;

        return view('dashboard.payment-confirmation', [
            'user' => $user,
            'whatsappUrl' => $whatsappUrl
        ]);
    }
}
