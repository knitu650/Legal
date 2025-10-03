<?php

namespace App\Services\Search;

class FilterService
{
    public function applyFilters($query, $filters)
    {
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $query = $query->where($field, $value);
            }
        }
        
        return $query;
    }
    
    public function applyDateRangeFilter($query, $field, $startDate, $endDate)
    {
        if ($startDate) {
            $query = $query->where($field, '>=', $startDate);
        }
        
        if ($endDate) {
            $query = $query->where($field, '<=', $endDate);
        }
        
        return $query;
    }
    
    public function applyPriceRangeFilter($query, $minPrice, $maxPrice)
    {
        if ($minPrice !== null) {
            $query = $query->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice !== null) {
            $query = $query->where('price', '<=', $maxPrice);
        }
        
        return $query;
    }
    
    public function applySorting($query, $sortBy, $sortOrder = 'ASC')
    {
        return $query->orderBy($sortBy, $sortOrder);
    }
}
