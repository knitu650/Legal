<?php

namespace App\Models;

use App\Core\Model;

class TicketReply extends Model
{
    protected $table = 'ticket_replies';
    protected $fillable = [
        'ticket_id', 'user_id', 'message', 'is_staff_reply'
    ];
    
    public $timestamps = false;
    
    public function ticket()
    {
        $ticketModel = new SupportTicket();
        return $ticketModel->find($this->ticket_id);
    }
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function isStaffReply()
    {
        return $this->is_staff_reply == 1;
    }
    
    public function isUserReply()
    {
        return $this->is_staff_reply == 0;
    }
}
