<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;
use App\Models\SupportTicket;

class SupportController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $ticketModel = new SupportTicket();
        
        $tickets = $ticketModel->where('user_id', $user->id)->get();
        
        $this->view('franchise/support/index', [
            'pageTitle' => 'Support',
            'tickets' => $tickets
        ]);
    }
}
