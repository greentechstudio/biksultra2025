<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TicketTypeController extends Controller
{
    /**
     * Display ticket types management
     */
    public function index()
    {
        $ticketTypes = TicketType::with('raceCategory')
            ->orderBy('race_category_id')
            ->orderBy('start_date')
            ->get();
        
        $stats = [
            'total_registrations' => User::count(),
            'paid_registrations' => User::where('status', 'paid')->count(),
            'early_bird_registrations' => 0, // TODO: Implement ticket type tracking
            'regular_registrations' => 0, // TODO: Implement ticket type tracking
        ];
        
        return view('admin.ticket-types.index', compact('ticketTypes', 'stats'));
    }

    /**
     * Show form to create new ticket type
     */
    public function create()
    {
        $raceCategories = \App\Models\RaceCategory::where('active', true)->get();
        return view('admin.ticket-types.create', compact('raceCategories'));
    }

    /**
     * Store new ticket type
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'race_category_id' => 'required|exists:race_categories,id',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quota' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);

        TicketType::create($request->all());

        return redirect()->route('admin.ticket-types.index')
                        ->with('success', 'Ticket type created successfully');
    }

    /**
     * Show form to edit ticket type
     */
    public function edit(TicketType $ticketType)
    {
        $raceCategories = \App\Models\RaceCategory::where('active', true)->get();
        return view('admin.ticket-types.edit', compact('ticketType', 'raceCategories'));
    }

    /**
     * Update ticket type
     */
    public function update(Request $request, TicketType $ticketType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'race_category_id' => 'required|exists:race_categories,id',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quota' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $ticketType->update($request->all());

        return redirect()->route('admin.ticket-types.index')
                        ->with('success', 'Ticket type updated successfully');
    }

    /**
     * Toggle ticket type active status
     */
    public function toggleActive(TicketType $ticketType)
    {
        $ticketType->update(['is_active' => !$ticketType->is_active]);
        
        $status = $ticketType->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Ticket type has been {$status}");
    }

    /**
     * Get ticket type statistics
     */
    public function getStats()
    {
        $stats = [
            'total_registrations' => User::count(),
            'paid_registrations' => User::where('status', 'paid')->count(),
            'pending_registrations' => User::where('status', 'pending')->count(),
            'active_ticket_types' => TicketType::where('is_active', true)->count(),
        ];
        
        // Get category-wise stats
        $categoryStats = \App\Models\RaceCategory::all()->map(function($category) {
            $users = User::where('race_category', $category->name)->get();
            $ticketTypes = TicketType::where('race_category_id', $category->id)->get();
            
            return [
                'category' => $category->name,
                'total_quota' => $ticketTypes->sum('quota'),
                'total_registered' => $users->count(),
                'total_paid' => $users->where('status', 'paid')->count(),
                'ticket_types' => $ticketTypes->map(function($ticket) {
                    return [
                        'name' => $ticket->name,
                        'price' => $ticket->price,
                        'quota' => $ticket->quota,
                        'registered' => $ticket->registered_count,
                        'remaining' => $ticket->getRemainingQuota(),
                        'active' => $ticket->isCurrentlyActive(),
                        'period' => $ticket->start_date->format('d M Y') . ' - ' . $ticket->end_date->format('d M Y')
                    ];
                })
            ];
        });
        
        return response()->json([
            'stats' => $stats,
            'category_stats' => $categoryStats
        ]);
    }
}
