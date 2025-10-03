<?php

namespace App\Controllers\Franchise;

use App\Core\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = [
            'stamp_papers' => [],
            'forms' => [],
            'supplies' => []
        ];
        
        $this->view('franchise/inventory/index', [
            'pageTitle' => 'Inventory Management',
            'inventory' => $inventory
        ]);
    }
    
    public function request()
    {
        $items = $this->request->input('items');
        
        // Process inventory request
        
        flash('success', 'Inventory request submitted!');
        $this->redirect('/franchise/inventory');
    }
}
