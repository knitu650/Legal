<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;

class SupportController extends Controller
{
    public function index()
    {
        $this->view('web/support/index', [
            'pageTitle' => 'Support'
        ]);
    }
    
    public function create()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        $this->view('web/support/create', [
            'pageTitle' => 'Create Support Ticket'
        ]);
    }
    
    public function store()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        $user = $this->auth();
        
        $data = [
            'subject' => $this->request->input('subject'),
            'description' => $this->request->input('description'),
            'priority' => $this->request->input('priority', 'medium'),
        ];
        
        $isValid = $this->validate($data, [
            'subject' => 'required|min:5',
            'description' => 'required|min:10',
        ]);
        
        if (!$isValid) {
            flash('error', 'Please fill all fields correctly.');
            $this->back();
            return;
        }
        
        $ticketModel = new SupportTicket();
        $ticket = $ticketModel->create([
            'user_id' => $user->id,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'status' => TICKET_OPEN
        ]);
        
        flash('success', 'Support ticket created successfully!');
        $this->redirect('/support/tickets/' . $ticket->id);
    }
}
