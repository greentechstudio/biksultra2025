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
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Basic stats (exclude admin users)
        $totalRegistrations = User::where('role', '!=', 'admin')->count();
        $paidRegistrations = User::where('role', '!=', 'admin')->where('payment_confirmed', true)->count();
        $pendingRegistrations = User::where('role', '!=', 'admin')->where('payment_confirmed', false)->count();
        $totalRevenue = User::where('role', '!=', 'admin')->where('payment_confirmed', true)->sum('payment_amount');
        $conversionRate = $totalRegistrations > 0 ? round(($paidRegistrations / $totalRegistrations) * 100, 1) : 0;
        
        // Calculate potential revenue from all registrations
        $totalPotentialRevenue = User::where('role', '!=', 'admin')
            ->join('race_categories', 'users.race_category', '=', 'race_categories.name')
            ->sum('race_categories.price');
        
        $unpaidPotentialRevenue = User::where('role', '!=', 'admin')
            ->where('payment_confirmed', false)
            ->join('race_categories', 'users.race_category', '=', 'race_categories.name')
            ->sum('race_categories.price');
        
        $stats = [
            'total_registrations' => $totalRegistrations,
            'paid_registrations' => $paidRegistrations,
            'pending_registrations' => $pendingRegistrations,
            'total_revenue' => $totalRevenue,
            'conversion_rate' => $conversionRate,
            'total_potential_revenue' => $totalPotentialRevenue,
            'unpaid_potential_revenue' => $unpaidPotentialRevenue,
            'recent_registrations' => User::where('role', '!=', 'admin')->latest()->take(5)->get(),
            'total_recent_registrations' => $totalRegistrations,
            'category_stats' => RaceCategory::withCount('users')->get(),
            'active_ticket_types' => TicketType::where('is_active', true)->count(),
            'whatsapp_queue_count' => DB::table('jobs')->where('queue', 'whatsapp')->count(),
            
            // Revenue by ticket type
            'revenue_by_ticket_type' => TicketType::withCount(['users' => function($query) {
                $query->where('role', '!=', 'admin');
            }])
            ->with(['users' => function($query) {
                $query->where('role', '!=', 'admin')->where('payment_confirmed', true);
            }])
            ->get()
            ->map(function($ticketType) {
                $paidCount = $ticketType->users->count();
                $revenue = $paidCount * $ticketType->price;
                return (object) [
                    'name' => $ticketType->name,
                    'price' => $ticketType->price,
                    'count' => $ticketType->users_count,
                    'revenue' => $revenue
                ];
            }),
            
            // Revenue by race category
            'revenue_by_category' => RaceCategory::get()
            ->map(function($category) {
                $totalUsers = User::where('role', '!=', 'admin')->where('race_category', $category->name)->count();
                $paidUsers = User::where('role', '!=', 'admin')->where('race_category', $category->name)->where('payment_confirmed', true)->count();
                $revenue = $paidUsers * $category->price;
                return (object) [
                    'name' => $category->name,
                    'price' => $category->price,
                    'count' => $totalUsers,
                    'revenue' => $revenue
                ];
            }),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function recentRegistrations()
    {
        $users = User::where('role', '!=', 'admin')
                    ->latest()
                    ->paginate(20);
        return view('admin.recent-registrations', compact('users'));
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
