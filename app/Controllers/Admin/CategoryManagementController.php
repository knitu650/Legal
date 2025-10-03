<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\DocumentCategory;

class CategoryManagementController extends Controller
{
    public function index()
    {
        $categoryModel = new DocumentCategory();
        $categories = $categoryModel->orderBy('sort_order', 'ASC')->get();
        
        $this->view('admin/categories/index', [
            'pageTitle' => 'Category Management',
            'categories' => $categories
        ]);
    }
    
    public function store()
    {
        $data = [
            'name' => $this->request->input('name'),
            'slug' => $this->request->input('slug'),
            'description' => $this->request->input('description'),
            'icon' => $this->request->input('icon'),
            'parent_id' => $this->request->input('parent_id'),
            'is_active' => $this->request->input('is_active', 1),
            'sort_order' => $this->request->input('sort_order', 0),
        ];
        
        $categoryModel = new DocumentCategory();
        $categoryModel->create($data);
        
        flash('success', 'Category created successfully!');
        $this->redirect('/admin/categories');
    }
    
    public function update($id)
    {
        $data = [
            'name' => $this->request->input('name'),
            'slug' => $this->request->input('slug'),
            'description' => $this->request->input('description'),
            'icon' => $this->request->input('icon'),
            'parent_id' => $this->request->input('parent_id'),
            'is_active' => $this->request->input('is_active'),
            'sort_order' => $this->request->input('sort_order'),
        ];
        
        $categoryModel = new DocumentCategory();
        $categoryModel->update($id, $data);
        
        flash('success', 'Category updated successfully!');
        $this->redirect('/admin/categories');
    }
    
    public function delete($id)
    {
        $categoryModel = new DocumentCategory();
        $categoryModel->delete($id);
        
        flash('success', 'Category deleted successfully!');
        $this->redirect('/admin/categories');
    }
}
