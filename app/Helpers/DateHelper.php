<?php

namespace App\Helpers;

class DateHelper
{
    public static function format($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }
    
    public static function toHuman($date)
    {
        $timestamp = strtotime($date);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $timestamp);
        }
    }
    
    public static function diffInDays($date1, $date2)
    {
        $timestamp1 = strtotime($date1);
        $timestamp2 = strtotime($date2);
        
        return floor(($timestamp2 - $timestamp1) / 86400);
    }
    
    public static function addDays($date, $days)
    {
        return date('Y-m-d H:i:s', strtotime($date . " +$days days"));
    }
    
    public static function isToday($date)
    {
        return date('Y-m-d', strtotime($date)) === date('Y-m-d');
    }
    
    public static function isPast($date)
    {
        return strtotime($date) < time();
    }
    
    public static function isFuture($date)
    {
        return strtotime($date) > time();
    }
}
