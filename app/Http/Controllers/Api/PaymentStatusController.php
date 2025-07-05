<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentStatusController extends Controller
{
    private $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Check payment status for a user
     */
    public function checkStatus(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $externalId = $request->input('external_id');
            
            if (!$userId && !$externalId) {
                return response()->json(['error' => 'user_id or external_id required'], 400);
            }
            
            $user = $userId ? User::find($userId) : User::where('xendit_external_id', $externalId)->first();
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            $response = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'payment_status' => $user->payment_status,
                'payment_confirmed' => $user->payment_confirmed,
                'payment_confirmed_at' => $user->payment_confirmed_at,
                'xendit_external_id' => $user->xendit_external_id,
                'xendit_invoice_id' => $user->xendit_invoice_id,
                'xendit_invoice_url' => $user->xendit_invoice_url,
                'payment_requested_at' => $user->payment_requested_at,
                'last_callback_data' => $user->xendit_callback_data
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Payment status check error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    
    /**
     * Manually update payment status
     */
    public function updateStatus(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $status = $request->input('status'); // paid, expired, failed
            
            if (!$userId || !$status) {
                return response()->json(['error' => 'user_id and status required'], 400);
            }
            
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            $updateData = [
                'payment_status' => strtolower($status)
            ];
            
            if (strtolower($status) === 'paid') {
                $updateData['payment_confirmed'] = true;
                $updateData['payment_confirmed_at'] = now();
                $updateData['status'] = 'active';
            } elseif (strtolower($status) === 'expired') {
                $updateData['status'] = 'expired';
            } elseif (strtolower($status) === 'failed') {
                $updateData['status'] = 'failed';
            }
            
            $user->update($updateData);
            
            Log::info('Payment status manually updated', [
                'user_id' => $user->id,
                'old_status' => $user->getOriginal('status'),
                'new_status' => $user->status,
                'updated_by' => 'manual'
            ]);
            
            return response()->json([
                'message' => 'Payment status updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'status' => $user->status,
                    'payment_status' => $user->payment_status,
                    'payment_confirmed' => $user->payment_confirmed
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment status update error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    
    /**
     * Get all users with pending payments
     */
    public function getPendingPayments()
    {
        try {
            $users = User::where('payment_status', 'pending')
                         ->whereNotNull('xendit_external_id')
                         ->select([
                             'id', 'name', 'email', 'status', 'payment_status',
                             'xendit_external_id', 'xendit_invoice_id', 
                             'payment_requested_at', 'created_at'
                         ])
                         ->orderBy('payment_requested_at', 'desc')
                         ->get();
            
            return response()->json([
                'pending_payments' => $users,
                'total_pending' => $users->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get pending payments error', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
