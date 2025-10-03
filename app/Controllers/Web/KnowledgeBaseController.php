<?php

namespace App\Controllers\Web;

use App\Core\Controller;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $this->view('web/knowledge-base/index', [
            'pageTitle' => 'Knowledge Base'
        ]);
    }
    
    public function faq()
    {
        $faqs = [
            [
                'category' => 'Getting Started',
                'questions' => [
                    [
                        'question' => 'How do I create a document?',
                        'answer' => 'Simply browse our template library, select a template, fill in your details, and download or sign electronically.'
                    ],
                    [
                        'question' => 'Is my data secure?',
                        'answer' => 'Yes, we use bank-level encryption to protect all your documents and personal information.'
                    ]
                ]
            ],
            [
                'category' => 'Billing',
                'questions' => [
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept all major credit/debit cards, UPI, net banking, and digital wallets through secure payment gateways.'
                    ],
                    [
                        'question' => 'Can I get a refund?',
                        'answer' => 'Refunds are available within 7 days of purchase if the service was not used. Please contact support for refund requests.'
                    ]
                ]
            ]
        ];
        
        $this->view('web/knowledge-base/faq', [
            'pageTitle' => 'Frequently Asked Questions',
            'faqs' => $faqs
        ]);
    }
}
