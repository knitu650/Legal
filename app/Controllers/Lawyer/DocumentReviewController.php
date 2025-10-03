<?php

namespace App\Controllers\Lawyer;

use App\Core\Controller;
use App\Models\Lawyer;

class DocumentReviewController extends Controller
{
    public function index()
    {
        $user = $this->auth();
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->where('user_id', $user->id)->first();
        
        // Get document review requests
        $reviewRequests = [];
        
        $this->view('lawyer/documents/review-requests', [
            'pageTitle' => 'Document Review Requests',
            'reviewRequests' => $reviewRequests
        ]);
    }
    
    public function show($id)
    {
        $this->view('lawyer/documents/review', [
            'pageTitle' => 'Review Document',
            'documentId' => $id
        ]);
    }
    
    public function complete($id)
    {
        $comments = $this->request->input('comments');
        
        // Mark review as complete
        
        flash('success', 'Document review completed!');
        $this->redirect('/lawyer/document-reviews');
    }
}
