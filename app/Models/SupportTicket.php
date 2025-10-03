<?php

namespace App\Models;

use App\Core\Model;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';
    protected $fillable = [
        'user_id', 'subject', 'description', 'status', 
        'priority', 'assigned_to'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function assignedTo()
    {
        if ($this->assigned_to) {
            $userModel = new User();
            return $userModel->find($this->assigned_to);
        }
        return null;
    }
    
    public function replies()
    {
        $replyModel = new TicketReply();
        return $replyModel->where('ticket_id', $this->id)
                         ->orderBy('created_at', 'ASC')
                         ->get();
    }
    
    public function isOpen()
    {
        return $this->status === TICKET_OPEN;
    }
    
    public function isInProgress()
    {
        return $this->status === TICKET_IN_PROGRESS;
    }
    
    public function isResolved()
    {
        return $this->status === TICKET_RESOLVED;
    }
    
    public function isClosed()
    {
        return $this->status === TICKET_CLOSED;
    }
    
    public function assign($userId)
    {
        return $this->update($this->id, [
            'assigned_to' => $userId,
            'status' => TICKET_IN_PROGRESS
        ]);
    }
    
    public function resolve()
    {
        return $this->update($this->id, [
            'status' => TICKET_RESOLVED
        ]);
    }
    
    public function close()
    {
        return $this->update($this->id, [
            'status' => TICKET_CLOSED
        ]);
    }
    
    public function reopen()
    {
        return $this->update($this->id, [
            'status' => TICKET_OPEN
        ]);
    }
}
