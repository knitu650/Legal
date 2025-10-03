<?php

namespace App\Models;

use App\Core\Model;

class Lawyer extends Model
{
    protected $table = 'lawyers';
    protected $fillable = [
        'user_id', 'specialization', 'experience_years', 
        'education', 'bar_council_number', 'verification_status',
        'hourly_rate', 'rating', 'total_consultations', 'bio', 'languages'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function consultations()
    {
        $consultationModel = new Consultation();
        return $consultationModel->where('lawyer_id', $this->id)->get();
    }
    
    public function reviews()
    {
        $reviewModel = new Review();
        return $reviewModel->where('lawyer_id', $this->id)->get();
    }
    
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }
    
    public function isPending()
    {
        return $this->verification_status === 'pending';
    }
    
    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }
    
    public function verify()
    {
        return $this->update($this->id, [
            'verification_status' => 'verified'
        ]);
    }
    
    public function reject()
    {
        return $this->update($this->id, [
            'verification_status' => 'rejected'
        ]);
    }
    
    public function getLanguages()
    {
        return json_decode($this->languages, true) ?? [];
    }
    
    public function updateRating()
    {
        $reviews = $this->reviews();
        if (empty($reviews)) {
            return;
        }
        
        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review->rating;
        }
        
        $averageRating = $totalRating / count($reviews);
        
        $this->update($this->id, [
            'rating' => round($averageRating, 2)
        ]);
    }
}
