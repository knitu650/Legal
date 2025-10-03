<?php

namespace App\Events;

class FranchiseApproved
{
    public $franchise;
    
    public function __construct($franchise)
    {
        $this->franchise = $franchise;
    }
    
    public function dispatch()
    {
        // Trigger listeners
        // Send approval email
        // Update analytics
    }
}
