<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;

class SupportTicketController extends Controller
{
    public function index()
    {
        $ticketModel = new SupportTicket();
        $status = $this->request->query('status');
        
        $query = $ticketModel;
        
        if ($status) {
            $query = $query->where('status', $status);
        }
        
        $tickets = $query->orderBy('created_at', 'DESC')->get();
        
        $this->view('admin/support/tickets', [
            'pageTitle' => 'Support Tickets',
            'tickets' => $tickets
        ]);
    }
    
    public function show($id)
    {
        $ticketModel = new SupportTicket();
        $ticket = $ticketModel->find($id);
        
        if (!$ticket) {
            flash('error', 'Ticket not found.');
            $this->redirect('/admin/support');
            return;
        }
        
        $replies = $ticket->replies();
        
        $this->view('admin/support/view-ticket', [
            'pageTitle' => 'Ticket #' . $ticket->id,
            'ticket' => $ticket,
            'replies' => $replies
        ]);
    }
    
    public function reply($id)
    {
        $user = $this->auth();
        $message = $this->request->input('message');
        
        if (!$message) {
            flash('error', 'Message is required.');
            $this->back();
            return;
        }
        
        $replyModel = new TicketReply();
        $replyModel->create([
            'ticket_id' => $id,
            'user_id' => $user->id,
            'message' => $message,
            'is_staff_reply' => 1
        ]);
        
        // Update ticket status to in_progress
        $ticketModel = new SupportTicket();
        $ticketModel->update($id, [
            'status' => TICKET_IN_PROGRESS
        ]);
        
        flash('success', 'Reply sent successfully!');
        $this->redirect('/admin/support/' . $id);
    }
    
    public function close($id)
    {
        $ticketModel = new SupportTicket();
        $ticket = $ticketModel->find($id);
        
        if ($ticket) {
            $ticket->close();
            flash('success', 'Ticket closed successfully!');
        }
        
        $this->redirect('/admin/support/' . $id);
    }
}
