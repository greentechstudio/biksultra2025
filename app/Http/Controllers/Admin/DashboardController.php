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
            'recent_registrations' => User::where('role', '!=', 'admin')
                ->with('ticketType')
                ->latest()
                ->take(5)
                ->get()
                ->map(function($user) {
                    return (object) [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'race_category' => $user->race_category,
                        'jersey_size' => $user->jersey_size,
                        'ticket_type' => $user->ticketType ? $user->ticketType->name : 'N/A',
                        'status' => $user->payment_confirmed ? 'paid' : 'pending',
                        'created_at' => $user->created_at
                    ];
                }),
            'total_recent_registrations' => $totalRegistrations,
            'category_stats' => $this->getCategoryStatsByTicketType(),
            'active_ticket_types' => TicketType::where('is_active', true)->count(),
            'whatsapp_queue_count' => DB::table('jobs')->where('queue', 'whatsapp')->count(),
            
            // Demographics and Statistics
            'demographics_stats' => $this->getDemographicsStatistics(),
            
            // Master A & Master B tables
            'master_a_users' => $this->getMasterAUsers(),
            'master_b_users' => $this->getMasterBUsers(),
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
        $query = User::where('role', '!=', 'admin')->with('ticketType');

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

        // Filter by jersey size
        if ($request->filled('jersey_size')) {
            $query->where('jersey_size', $request->jersey_size);
        }

        // Filter by ticket type
        if ($request->filled('ticket_type')) {
            $query->whereHas('ticketType', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->ticket_type . '%');
            });
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

        // Get jersey sizes for filter dropdown
        $jerseySizes = User::where('role', '!=', 'admin')
                          ->whereNotNull('jersey_size')
                          ->distinct()
                          ->pluck('jersey_size')
                          ->sort();

        // Get ticket types for filter dropdown
        $ticketTypes = TicketType::where('is_active', true)
                                ->distinct()
                                ->pluck('name')
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

        return view('admin.recent-registrations', compact('users', 'totalCount', 'raceCategories', 'jerseySizes', 'ticketTypes', 'stats'));
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
    
    /**
     * Get category statistics grouped by ticket type (Early Bird vs Regular)
     */
    private function getCategoryStatsByTicketType()
    {
        $categories = RaceCategory::all();
        $categoryStats = [];
        
        foreach ($categories as $category) {
            // Get ticket types for this category
            $ticketTypes = TicketType::where('race_category_id', $category->id)->get();
            
            foreach ($ticketTypes as $ticketType) {
                // Count users for this specific ticket type
                $userCount = User::where('role', '!=', 'admin')
                    ->where('race_category', $category->name)
                    ->where('ticket_type_id', $ticketType->id)
                    ->count();
                
                // Count paid users for this ticket type
                $paidCount = User::where('role', '!=', 'admin')
                    ->where('race_category', $category->name)
                    ->where('ticket_type_id', $ticketType->id)
                    ->where('payment_confirmed', true)
                    ->count();
                
                // Calculate revenue for this ticket type
                $revenue = User::where('role', '!=', 'admin')
                    ->where('race_category', $category->name)
                    ->where('ticket_type_id', $ticketType->id)
                    ->where('payment_confirmed', true)
                    ->sum('payment_amount');
                
                $categoryStats[] = (object) [
                    'id' => $category->id . '-' . $ticketType->id,
                    'category_name' => $category->name,
                    'ticket_type_name' => $ticketType->name,
                    'name' => $category->name . ' (' . $ticketType->name . ')',
                    'price' => $ticketType->price,
                    'users_count' => $userCount,
                    'paid_count' => $paidCount,
                    'pending_count' => $userCount - $paidCount,
                    'revenue' => $revenue,
                    'ticket_type' => $ticketType,
                    'category' => $category
                ];
            }
        }
        
        return collect($categoryStats)->sortBy(['category_name', 'ticket_type_name']);
    }

    /**
     * Get demographics statistics for the dashboard
     */
    private function getDemographicsStatistics()
    {
        $demographics = [];
        
        // Get current year for calculations
        $currentYear = date('Y');
        
        // Define age ranges based on birth year ranges
        $ageRanges = [
            '15-20' => [$currentYear - 20, $currentYear - 15], // Birth years
            '21-25' => [$currentYear - 25, $currentYear - 21],
            '26-30' => [$currentYear - 30, $currentYear - 26],
            '31-35' => [$currentYear - 35, $currentYear - 31],
            '36-40' => [$currentYear - 40, $currentYear - 36],
            '41-45' => [$currentYear - 45, $currentYear - 41],
            '46-50' => [$currentYear - 50, $currentYear - 46],
            '51+' => [$currentYear - 70, $currentYear - 51], // 51+ years old
        ];
        
        // Get all available race categories from database (paid users only)
        $availableCategories = User::where('role', '!=', 'admin')
            ->where('payment_confirmed', true) // Only paid users
            ->whereNotNull('race_category')
            ->distinct()
            ->pluck('race_category')
            ->toArray();
        
        // If no categories found, use default ones
        if (empty($availableCategories)) {
            $availableCategories = ['5K', '10K', '21K', 'Half Marathon', 'Full Marathon'];
        }
        
        // Create chart data structure based on actual birth dates (paid users only)
        $chartData = [];
        $ageStats = [];
        
        foreach ($ageRanges as $ageGroup => $birthYearRange) {
            $groupData = [];
            $totalInGroup = 0;
            
            foreach ($availableCategories as $category) {
                // Get actual count from database using birth_date (paid users only)
                $count = User::where('role', '!=', 'admin')
                    ->where('race_category', $category)
                    ->where('payment_confirmed', true) // Only paid users
                    ->whereNotNull('birth_date')
                    ->whereRaw('YEAR(birth_date) BETWEEN ? AND ?', [$birthYearRange[0], $birthYearRange[1]])
                    ->count();
                
                $groupData[$category] = $count;
                $totalInGroup += $count;
            }
            
            $chartData[$ageGroup] = $groupData;
            
            // Calculate paid count for this age group (already filtered, so same as totalInGroup)
            $paidCount = $totalInGroup; // All users in this data are already paid
            
            $ageStats[] = (object) [
                'group' => $ageGroup,
                'count' => $totalInGroup,
                'paid_count' => $paidCount,
                'pending_count' => 0, // No pending users in this filtered data
                'percentage' => 0, // Will be calculated after getting total
                'birth_year_range' => $birthYearRange[0] . ' - ' . $birthYearRange[1]
            ];
        }
        
        // Calculate age group percentages
        $totalAgeUsers = collect($ageStats)->sum('count');
        foreach ($ageStats as $stat) {
            $stat->percentage = $totalAgeUsers > 0 ? round(($stat->count / $totalAgeUsers) * 100, 1) : 0;
        }
        
        // Race category statistics (paid users only)
        $raceStats = User::where('role', '!=', 'admin')
            ->where('payment_confirmed', true) // Only paid users
            ->whereNotNull('race_category')
            ->select('race_category')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('COUNT(*) as paid_count') // All users in this query are paid
            ->groupBy('race_category')
            ->get()
            ->map(function($item) {
                return (object) [
                    'category' => $item->race_category,
                    'count' => $item->count,
                    'paid_count' => $item->paid_count,
                    'pending_count' => 0, // No pending users in this filtered data
                    'percentage' => 0 // Will be calculated after getting total
                ];
            });
        
        // Calculate race category percentages
        $totalRaceUsers = $raceStats->sum('count');
        foreach ($raceStats as $stat) {
            $stat->percentage = $totalRaceUsers > 0 ? round(($stat->count / $totalRaceUsers) * 100, 1) : 0;
        }
        
        // City/Regency statistics (top 10)
        $cityStats = User::where('role', '!=', 'admin')
            ->whereNotNull('regency_name')
            ->select('regency_name')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('COUNT(CASE WHEN payment_confirmed = 1 THEN 1 END) as paid_count')
            ->groupBy('regency_name')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get()
            ->map(function($item) {
                return (object) [
                    'city' => $item->regency_name,
                    'count' => $item->count,
                    'paid_count' => $item->paid_count,
                    'pending_count' => $item->count - $item->paid_count,
                    'percentage' => 0 // Will be calculated after getting total
                ];
            });
        
        // Calculate city percentages
        $totalCityUsers = $cityStats->sum('count');
        foreach ($cityStats as $stat) {
            $stat->percentage = $totalCityUsers > 0 ? round(($stat->count / $totalCityUsers) * 100, 1) : 0;
        }
        
        return [
            'age_groups' => $ageStats,
            'race_categories' => $raceStats,
            'cities' => $cityStats,
            'chart_data' => $chartData, // Add chart data for Chart.js
            'totals' => [
                'age_users' => $totalAgeUsers,
                'race_users' => $totalRaceUsers,
                'city_users' => $totalCityUsers
            ]
        ];
    }
    
    /**
     * Get age distribution factor for simulation (legacy method)
     * This is a temporary method until we have actual age data
     */
    private function getAgeDistributionFactor($ageGroup)
    {
        // Map legacy age groups to new system
        $legacyFactors = [
            '18-25' => 0.25,
            '26-35' => 0.35,
            '36-45' => 0.25,
            '46-55' => 0.10,
            '56+' => 0.05
        ];
        
        return $legacyFactors[$ageGroup] ?? 0.2;
    }
    
    /**
     * Get Master A users (age 40-49, category 21K, paid status only)
     */
    private function getMasterAUsers()
    {
        $currentYear = date('Y');
        $startYear = $currentYear - 49; // 49 years old
        $endYear = $currentYear - 40;   // 40 years old
        
        return User::where('role', '!=', 'admin')
            ->where('race_category', '21K')
            ->where('payment_confirmed', true) // Only paid users
            ->whereNotNull('birth_date')
            ->whereRaw('YEAR(birth_date) BETWEEN ? AND ?', [$startYear, $endYear])
            ->select('id', 'name', 'email', 'birth_date', 'payment_confirmed', 'created_at')
            ->orderBy('name')
            ->get()
            ->map(function($user) {
                $birthYear = date('Y', strtotime($user->birth_date));
                $age = date('Y') - $birthYear;
                return (object) [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'age' => $age,
                    'birth_year' => $birthYear,
                    'payment_confirmed' => $user->payment_confirmed,
                    'created_at' => $user->created_at
                ];
            });
    }
    
    /**
     * Get Master B users (age 50+, category 21K, paid status only)
     */
    private function getMasterBUsers()
    {
        $currentYear = date('Y');
        $endYear = $currentYear - 50;   // 50 years old and above
        
        return User::where('role', '!=', 'admin')
            ->where('race_category', '21K')
            ->where('payment_confirmed', true) // Only paid users
            ->whereNotNull('birth_date')
            ->whereRaw('YEAR(birth_date) <= ?', [$endYear])
            ->select('id', 'name', 'email', 'birth_date', 'payment_confirmed', 'created_at')
            ->orderBy('name')
            ->get()
            ->map(function($user) {
                $birthYear = date('Y', strtotime($user->birth_date));
                $age = date('Y') - $birthYear;
                return (object) [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'age' => $age,
                    'birth_year' => $birthYear,
                    'payment_confirmed' => $user->payment_confirmed,
                    'created_at' => $user->created_at
                ];
            });
    }
}
