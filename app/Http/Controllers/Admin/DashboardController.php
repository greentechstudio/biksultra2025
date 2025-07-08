<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use App\Models\TicketType;
use App\Models\RaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Basic stats (exclude admin users)
        $stats = [
            'total_registrations' => User::where('role', '!=', 'admin')->count(),
            'paid_registrations' => User::where('role', '!=', 'admin')->where('payment_confirmed', true)->count(),
            'pending_registrations' => User::where('role', '!=', 'admin')->where('payment_confirmed', false)->count(),
            'total_revenue' => User::where('role', '!=', 'admin')->where('payment_confirmed', true)->sum('payment_amount'),
            'recent_registrations' => User::where('role', '!=', 'admin')->latest()->take(10)->get(),
            'category_stats' => RaceCategory::withCount('users')->get(),
            'active_ticket_types' => TicketType::where('is_active', true)->count(),
            'whatsapp_queue_count' => DB::table('jobs')->where('queue', 'whatsapp')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function whatsappVerification()
    {
        $users = User::where('role', '!=', 'admin')
                    ->where('whatsapp_verified', false)
                    ->paginate(20);
        return view('admin.whatsapp-verification', compact('users'));
    }

    public function paymentConfirmation()
    {
        $users = User::where('role', '!=', 'admin')
                    ->where('payment_confirmed', false)
                    ->paginate(20);
        return view('admin.payment-confirmation', compact('users'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return back()->with('success', 'Profile updated successfully.');
    }
}
