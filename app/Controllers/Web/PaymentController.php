<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Transaction;
use App\Models\Document;
use App\Services\Payment\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;
    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->paymentService = new PaymentService();
    }
    
    public function create()
    {
        $amount = $this->request->input('amount');
        $type = $this->request->input('type');
        $documentId = $this->request->input('document_id');
        
        if (!$this->isAuthenticated()) {
            $this->json(['error' => 'Authentication required'], 401);
            return;
        }
        
        $user = $this->auth();
        
        // Create transaction record
        $transactionModel = new Transaction();
        $transaction = $transactionModel->create([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'status' => PAYMENT_PENDING,
            'metadata' => json_encode(['document_id' => $documentId])
        ]);
        
        // Create payment order
        $order = $this->paymentService->createOrder($transaction->id, $amount);
        
        $this->json([
            'success' => true,
            'order_id' => $order['order_id'],
            'amount' => $amount,
            'transaction_id' => $transaction->id
        ]);
    }
    
    public function verify()
    {
        $paymentId = $this->request->input('payment_id');
        $orderId = $this->request->input('order_id');
        $signature = $this->request->input('signature');
        $transactionId = $this->request->input('transaction_id');
        
        $verified = $this->paymentService->verifyPayment($paymentId, $orderId, $signature);
        
        if ($verified) {
            $transactionModel = new Transaction();
            $transactionModel->update($transactionId, [
                'status' => PAYMENT_COMPLETED,
                'payment_id' => $paymentId,
                'paid_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->json(['success' => true, 'message' => 'Payment verified successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Payment verification failed'], 400);
        }
    }
    
    public function razorpayWebhook()
    {
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';
        
        // Verify webhook signature and process
        $this->paymentService->handleWebhook($payload, $signature);
        
        http_response_code(200);
    }
    
    public function stripeWebhook()
    {
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        
        // Process Stripe webhook
        http_response_code(200);
    }
}
