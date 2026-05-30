<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format number to Indonesian Rupiah currency
     * 
     * @param float|int $amount
     * @param bool $showSymbol
     * @return string
     */
    public static function formatIDR($amount, $showSymbol = true)
    {
        if ($amount === null || $amount === '') {
            return $showSymbol ? 'Rp 0' : '0';
        }
        
        $formatted = number_format($amount, 0, ',', '.');
        
        return $showSymbol ? 'Rp ' . $formatted : $formatted;
    }
    
    /**
     * Format number to Indonesian Rupiah currency with decimal places
     * 
     * @param float|int $amount
     * @param int $decimals
     * @param bool $showSymbol
     * @return string
     */
    public static function formatIDRWithDecimals($amount, $decimals = 2, $showSymbol = true)
    {
        if ($amount === null || $amount === '') {
            return $showSymbol ? 'Rp 0' : '0';
        }
        
        $formatted = number_format($amount, $decimals, ',', '.');
        
        return $showSymbol ? 'Rp ' . $formatted : $formatted;
    }
    
    /**
     * Format large amounts with K, M, B suffixes
     * 
     * @param float|int $amount
     * @param bool $showSymbol
     * @return string
     */
    public static function formatIDRShort($amount, $showSymbol = true)
    {
        if ($amount === null || $amount === '') {
            return $showSymbol ? 'Rp 0' : '0';
        }
        
        $prefix = $showSymbol ? 'Rp ' : '';
        
        if ($amount >= 1000000000) {
            return $prefix . number_format($amount / 1000000000, 1, ',', '.') . 'B';
        } elseif ($amount >= 1000000) {
            return $prefix . number_format($amount / 1000000, 1, ',', '.') . 'M';
        } elseif ($amount >= 1000) {
            return $prefix . number_format($amount / 1000, 1, ',', '.') . 'K';
        }
        
        return $prefix . number_format($amount, 0, ',', '.');
    }
}
