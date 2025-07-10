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
        // FIXED: Use payment_amount from users table, not current race_categories price
        $totalPotentialRevenue = User::where('role', '!=', 'admin')
            ->whereNotNull('payment_amount')
            ->sum('payment_amount');
        
        // Calculate unpaid potential revenue (only from pending payments)
        // FIXED: Use payment_amount from users table, not current race_categories price
        $unpaidPotentialRevenue = User::where('role', '!=', 'admin')
            ->where('payment_confirmed', false)
            ->whereNotNull('payment_amount')
            ->sum('payment_amount');
        
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
            // FIXED: Use actual payment_amount from users, not ticket_type price
            'revenue_by_ticket_type' => TicketType::withCount(['users' => function($query) {
                $query->where('role', '!=', 'admin');
            }])
            ->with(['users' => function($query) {
                $query->where('role', '!=', 'admin')->where('payment_confirmed', true);
            }])
            ->get()
            ->map(function($ticketType) {
                $paidCount = $ticketType->users->count();
                
                // Use actual payment amounts instead of ticket type price
                $revenue = User::where('role', '!=', 'admin')
                    ->where('ticket_type_id', $ticketType->id)
                    ->where('payment_confirmed', true)
                    ->sum('payment_amount');
                
                return (object) [
                    'name' => $ticketType->name,
                    'price' => $ticketType->price, // Ticket type price for reference
                    'count' => $ticketType->users_count,
                    'revenue' => $revenue // Actual revenue from payments
                ];
            }),
            
            // Revenue by race category
            // FIXED: Use actual payment_amount from users, not current category price
            'revenue_by_category' => RaceCategory::get()
            ->map(function($category) {
                $totalUsers = User::where('role', '!=', 'admin')->where('race_category', $category->name)->count();
                $paidUsers = User::where('role', '!=', 'admin')->where('race_category', $category->name)->where('payment_confirmed', true)->count();
                
                // Use actual payment amounts instead of current category price
                $revenue = User::where('role', '!=', 'admin')
                    ->where('race_category', $category->name)
                    ->where('payment_confirmed', true)
                    ->sum('payment_amount');
                
                return (object) [
                    'name' => $category->name,
                    'price' => $category->price, // Current price for reference
                    'count' => $totalUsers,
                    'revenue' => $revenue // Actual revenue from payments
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

    public function recentRegistrations(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // Filter by payment status
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->where('payment_confirmed', true);
            } elseif ($request->payment_status === 'pending') {
                $query->where('payment_confirmed', false);
            }
        }

        // Filter by WhatsApp verification
        if ($request->filled('whatsapp_verified')) {
            $query->where('whatsapp_verified', $request->whatsapp_verified === '1');
        }

        // Filter by race category
        if ($request->filled('race_category')) {
            $query->where('race_category', $request->race_category);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by name, email, or WhatsApp
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%");
            });
        }

        // Get total count before pagination
        $totalCount = $query->count();
        
        // Get statistics for current filters
        $stats = [
            'total' => $totalCount,
            'paid' => (clone $query)->where('payment_confirmed', true)->count(),
            'pending' => (clone $query)->where('payment_confirmed', false)->count(),
            'whatsapp_verified' => (clone $query)->where('whatsapp_verified', true)->count(),
            'whatsapp_pending' => (clone $query)->where('whatsapp_verified', false)->count(),
        ];
        
        // Apply pagination
        $users = $query->latest()->paginate(20)->withQueryString();

        // Get race categories for filter dropdown
        $raceCategories = User::where('role', '!=', 'admin')
                             ->whereNotNull('race_category')
                             ->distinct()
                             ->pluck('race_category')
                             ->sort();

        // Ensure all variables are defined
        $totalCount = $totalCount ?? 0;
        $stats = $stats ?? [
            'total' => 0,
            'paid' => 0,
            'pending' => 0,
            'whatsapp_verified' => 0,
            'whatsapp_pending' => 0,
        ];

        return view('admin.recent-registrations', compact('users', 'totalCount', 'raceCategories', 'stats'));
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

    public function exportRecentRegistrations(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // Apply same filters as in recentRegistrations method
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->where('payment_confirmed', true);
            } elseif ($request->payment_status === 'pending') {
                $query->where('payment_confirmed', false);
            }
        }

        if ($request->filled('whatsapp_verified')) {
            $query->where('whatsapp_verified', $request->whatsapp_verified === '1');
        }

        if ($request->filled('race_category')) {
            $query->where('race_category', $request->race_category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();

        $filename = 'registrations_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use($users) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV headers
            fputcsv($file, [
                'Name',
                'Email', 
                'WhatsApp',
                'Race Category',
                'Payment Status',
                'WhatsApp Verified',
                'Birth Date',
                'Gender',
                'Blood Type',
                'Jersey Size',
                'Emergency Contact',
                'Emergency Phone',
                'Registration Date',
                'Payment Confirmed Date'
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->whatsapp_number,
                    $user->race_category,
                    $user->payment_confirmed ? 'Paid' : 'Pending',
                    $user->whatsapp_verified ? 'Yes' : 'No',
                    $user->birth_date,
                    $user->gender,
                    $user->blood_type,
                    $user->jersey_size,
                    $user->emergency_contact,
                    $user->emergency_phone,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->payment_confirmed_at ? $user->payment_confirmed_at->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
