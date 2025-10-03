<?php

namespace App\Controllers\User;

use App\Core\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;

class SupportTicketController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $ticketModel = new SupportTicket();
        
        $tickets = $ticketModel->where('user_id', $user->id)
                              ->orderBy('created_at', 'DESC')
                              ->get();
        
        $this->view('user/support/tickets', [
            'pageTitle' => 'Support Tickets',
            'tickets' => $tickets
        ]);
    }
    
    public function create()
    {
        $this->view('user/support/create-ticket', [
            'pageTitle' => 'Create Support Ticket'
        ]);
    }
    
    public function store()
    {
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
        $this->redirect('/user/support/' . $ticket->id);
    }
    
    public function show($id)
    {
        $user = $this->auth();
        $ticketModel = new SupportTicket();
        $ticket = $ticketModel->find($id);
        
        if (!$ticket || $ticket->user_id != $user->id) {
            flash('error', 'Ticket not found.');
            $this->redirect('/user/support');
            return;
        }
        
        $replies = $ticket->replies();
        
        $this->view('user/support/view-ticket', [
            'pageTitle' => 'Ticket #' . $ticket->id,
            'ticket' => $ticket,
            'replies' => $replies
        ]);
    }
    
    public function reply($id)
    {
        $user = $this->auth();
        $ticketModel = new SupportTicket();
        $ticket = $ticketModel->find($id);
        
        if (!$ticket || $ticket->user_id != $user->id) {
            $this->json(['error' => 'Ticket not found'], 404);
            return;
        }
        
        $message = $this->request->input('message');
        
        if (!$message) {
            $this->json(['error' => 'Message is required'], 400);
            return;
        }
        
        $replyModel = new TicketReply();
        $replyModel->create([
            'ticket_id' => $id,
            'user_id' => $user->id,
            'message' => $message,
            'is_staff_reply' => 0
        ]);
        
        $this->json(['success' => true]);
    }
}
