<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = [
        'user_id', 'reviewable_type', 'reviewable_id',
        'rating', 'title', 'comment', 'is_verified', 'is_approved'
    ];
    
    public function user()
    {
        $userModel = new User();
        return $userModel->find($this->user_id);
    }
    
    public function getReviewable()
    {
        switch ($this->reviewable_type) {
            case 'lawyer':
                $model = new Lawyer();
                break;
            case 'template':
                $model = new DocumentTemplate();
                break;
            default:
                return null;
        }
        
        return $model->find($this->reviewable_id);
    }
    
    public function isVerified()
    {
        return $this->is_verified == 1;
    }
    
    public function isApproved()
    {
        return $this->is_approved == 1;
    }
    
    public function approve()
    {
        return $this->update($this->id, [
            'is_approved' => 1
        ]);
    }
    
    public function reject()
    {
        return $this->update($this->id, [
            'is_approved' => 0
        ]);
    }
    
    public function verify()
    {
        return $this->update($this->id, [
            'is_verified' => 1
        ]);
    }
    
    public static function averageRating($reviewableType, $reviewableId)
    {
        $instance = new self();
        $reviews = $instance->where('reviewable_type', $reviewableType)
                           ->where('reviewable_id', $reviewableId)
                           ->where('is_approved', 1)
                           ->get();
        
        if (empty($reviews)) {
            return 0;
        }
        
        $total = 0;
        foreach ($reviews as $review) {
            $total += $review->rating;
        }
        
        return round($total / count($reviews), 2);
    }
}
