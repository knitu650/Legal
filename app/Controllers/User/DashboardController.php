<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\Document;
use App\Models\Subscription;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        
        $documentModel = new Document();
        $subscriptionModel = new Subscription();
        $transactionModel = new Transaction();
        
        // Get statistics
        $stats = [
            'total_documents' => $documentModel->where('user_id', $user->id)->count(),
            'draft_documents' => $documentModel->where('user_id', $user->id)
                                              ->where('status', DOC_STATUS_DRAFT)
                                              ->count(),
            'completed_documents' => $documentModel->where('user_id', $user->id)
                                                   ->where('status', DOC_STATUS_COMPLETED)
                                                   ->count(),
            'signed_documents' => $documentModel->where('user_id', $user->id)
                                               ->where('status', DOC_STATUS_SIGNED)
                                               ->count(),
        ];
        
        // Recent documents
        $recentDocuments = $documentModel->where('user_id', $user->id)
                                        ->orderBy('created_at', 'DESC')
                                        ->limit(5)
                                        ->get();
        
        // Active subscription
        $activeSubscription = $subscriptionModel->where('user_id', $user->id)
                                               ->where('status', SUBSCRIPTION_ACTIVE)
                                               ->first();
        
        // Recent transactions
        $recentTransactions = $transactionModel->where('user_id', $user->id)
                                              ->orderBy('created_at', 'DESC')
                                              ->limit(5)
                                              ->get();
        
        $this->view('user/dashboard/index', [
            'pageTitle' => 'Dashboard',
            'user' => $user,
            'stats' => $stats,
            'recentDocuments' => $recentDocuments,
            'activeSubscription' => $activeSubscription,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
