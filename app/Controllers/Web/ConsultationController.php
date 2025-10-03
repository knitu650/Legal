<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Lawyer;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    public function index()
    {
        $this->view('web/consultation/index', [
            'pageTitle' => 'Legal Consultation'
        ]);
    }
    
    public function lawyers()
    {
        $lawyerModel = new Lawyer();
        
        $specialization = $this->request->query('specialization');
        $rating = $this->request->query('rating');
        
        $query = $lawyerModel->where('verification_status', 'verified');
        
        if ($specialization) {
            $query = $query->where('specialization', 'LIKE', "%$specialization%");
        }
        
        if ($rating) {
            $query = $query->where('rating', '>=', $rating);
        }
        
        $lawyers = $query->orderBy('rating', 'DESC')->get();
        
        $this->view('web/consultation/lawyers', [
            'pageTitle' => 'Find Lawyers',
            'lawyers' => $lawyers
        ]);
    }
    
    public function book($lawyerId)
    {
        if (!$this->isAuthenticated()) {
            flash('error', 'Please login to book a consultation.');
            $this->redirect('/login');
            return;
        }
        
        $lawyerModel = new Lawyer();
        $lawyer = $lawyerModel->find($lawyerId);
        
        if (!$lawyer || !$lawyer->isVerified()) {
            flash('error', 'Lawyer not found.');
            $this->redirect('/consultation/lawyers');
            return;
        }
        
        $this->view('web/consultation/book', [
            'pageTitle' => 'Book Consultation',
            'lawyer' => $lawyer
        ]);
    }
}
