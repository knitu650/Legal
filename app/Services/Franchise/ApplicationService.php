<?php

namespace App\Services\Franchise;

use App\Models\FranchiseApplication;
use App\Services\Notification\EmailService;

class ApplicationService
{
    protected $emailService;
    
    public function __construct()
    {
        $this->emailService = new EmailService();
    }
    
    public function submitApplication($data)
    {
        $applicationModel = new FranchiseApplication();
        
        $application = $applicationModel->create([
            'user_id' => $data['user_id'],
            'business_name' => $data['business_name'],
            'location_id' => $data['location_id'],
            'investment_capacity' => $data['investment_capacity'],
            'experience' => $data['experience'],
            'proposed_territory' => json_encode($data['proposed_territory'] ?? []),
            'documents' => json_encode($data['documents'] ?? []),
            'status' => 'pending'
        ]);
        
        // Send confirmation email
        $this->emailService->sendTemplate($data['email'], 'franchise/application-received', [
            'subject' => 'Franchise Application Received',
            'business_name' => $data['business_name']
        ]);
        
        return $application;
    }
    
    public function approveApplication($applicationId, $reviewerId)
    {
        $applicationModel = new FranchiseApplication();
        $application = $applicationModel->find($applicationId);
        
        if (!$application) {
            return false;
        }
        
        $application->approve($reviewerId);
        
        // Send approval email
        
        return true;
    }
    
    public function rejectApplication($applicationId, $reviewerId, $reason)
    {
        $applicationModel = new FranchiseApplication();
        $application = $applicationModel->find($applicationId);
        
        if (!$application) {
            return false;
        }
        
        $application->reject($reviewerId, $reason);
        
        // Send rejection email
        
        return true;
    }
}
