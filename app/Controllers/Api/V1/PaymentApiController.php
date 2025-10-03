<?php

namespace App\Controllers\Api\V1;

use App\Core\Controller;
use App\Services\Payment\PaymentService;
use App\Models\Transaction;

class PaymentApiController extends Controller
{
    protected $paymentService;
    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->paymentService = new PaymentService();
    }
    
    public function create()
    {
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Unauthenticated'], 401);
            return;
        }
        
        $user = $this->auth();
        $amount = $this->request->input('amount');
        $type = $this->request->input('type');
        
        // Create transaction
        $transactionModel = new Transaction();
        $transaction = $transactionModel->create([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'status' => PAYMENT_PENDING
        ]);
        
        // Create payment order
        $order = $this->paymentService->createOrder($transaction->id, $amount);
        
        $this->json([
            'success' => true,
            'transaction_id' => $transaction->id,
            'order' => $order
        ]);
    }
    
    public function verify()
    {
        $paymentId = $this->request->input('payment_id');
        $orderId = $this->request->input('order_id');
        $signature = $this->request->input('signature');
        
        $verified = $this->paymentService->verifyPayment($paymentId, $orderId, $signature);
        
        $this->json([
            'success' => $verified,
            'message' => $verified ? 'Payment verified' : 'Verification failed'
        ]);
    }
}
