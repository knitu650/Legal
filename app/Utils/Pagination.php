<?php

namespace App\Utils;

class Pagination
{
    protected $total;
    protected $perPage;
    protected $currentPage;
    protected $lastPage;
    
    public function __construct($total, $perPage = 15, $currentPage = 1)
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->lastPage = ceil($total / $perPage);
    }
    
    public function render()
    {
        $html = '<nav><ul class="pagination">';
        
        // Previous button
        if ($this->currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="?page=' . ($this->currentPage - 1) . '">Previous</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        
        // Page numbers
        for ($i = 1; $i <= $this->lastPage; $i++) {
            if ($i == $this->currentPage) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
            }
        }
        
        // Next button
        if ($this->currentPage < $this->lastPage) {
            $html .= '<li class="page-item"><a class="page-link" href="?page=' . ($this->currentPage + 1) . '">Next</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        
        $html .= '</ul></nav>';
        
        return $html;
    }
    
    public function getOffset()
    {
        return ($this->currentPage - 1) * $this->perPage;
    }
    
    public function hasMorePages()
    {
        return $this->currentPage < $this->lastPage;
    }
}
