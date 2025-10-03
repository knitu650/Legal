<?php

namespace App\Models;

use App\Core\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $fillable = [
        'name', 'state', 'city', 'pincode', 
        'latitude', 'longitude', 'is_active'
    ];
    
    public function franchises()
    {
        $franchiseModel = new Franchise();
        return $franchiseModel->where('location_id', $this->id)->get();
    }
    
    public function isActive()
    {
        return $this->is_active == 1;
    }
    
    public function getFullAddress()
    {
        return "{$this->name}, {$this->city}, {$this->state} - {$this->pincode}";
    }
    
    public function getCoordinates()
    {
        return [
            'lat' => (float)$this->latitude,
            'lng' => (float)$this->longitude
        ];
    }
    
    public static function findByPincode($pincode)
    {
        $instance = new self();
        return $instance->where('pincode', $pincode)->first();
    }
    
    public static function findByCity($city)
    {
        $instance = new self();
        return $instance->where('city', $city)->get();
    }
    
    public static function findByState($state)
    {
        $instance = new self();
        return $instance->where('state', $state)->get();
    }
    
    public function franchisesCount()
    {
        $franchiseModel = new Franchise();
        return $franchiseModel->where('location_id', $this->id)->count();
    }
}
