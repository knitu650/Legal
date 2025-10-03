<?php

namespace App\Models;

use App\Core\Model;

class Consultation extends Model
{
    protected $table = 'consultations';
    protected $fillable = [
        'user_id', 'lawyer_id', 'type', 'status', 
        'scheduled_at', 'duration_minutes', 'amount', 
        'meeting_link', 'notes', 'rating', 'review'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function lawyer()
    {
        $lawyerModel = new Lawyer();
        return $lawyerModel->find($this->lawyer_id);
    }
    
    public function isPending()
    {
        return $this->status === CONSULTATION_PENDING;
    }
    
    public function isConfirmed()
    {
        return $this->status === CONSULTATION_CONFIRMED;
    }
    
    public function isCompleted()
    {
        return $this->status === CONSULTATION_COMPLETED;
    }
    
    public function isCancelled()
    {
        return $this->status === CONSULTATION_CANCELLED;
    }
    
    public function confirm()
    {
        return $this->update($this->id, [
            'status' => CONSULTATION_CONFIRMED
        ]);
    }
    
    public function complete($notes = '')
    {
        return $this->update($this->id, [
            'status' => CONSULTATION_COMPLETED,
            'notes' => $notes
        ]);
    }
    
    public function cancel()
    {
        return $this->update($this->id, [
            'status' => CONSULTATION_CANCELLED
        ]);
    }
    
    public function addReview($rating, $reviewText)
    {
        return $this->update($this->id, [
            'rating' => $rating,
            'review' => $reviewText
        ]);
    }
    
    public function isUpcoming()
    {
        return strtotime($this->scheduled_at) > time() && !$this->isCompleted() && !$this->isCancelled();
    }
}
