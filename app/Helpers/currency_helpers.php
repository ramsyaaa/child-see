<?php

if (!function_exists('formatIDR')) {
    /**
     * Format number to Indonesian Rupiah currency
     * 
     * @param float|int $amount
     * @param bool $showSymbol
     * @return string
     */
    function formatIDR($amount, $showSymbol = true)
    {
        if ($amount === null || $amount === '') {
            return $showSymbol ? 'Rp 0' : '0';
        }
        
        $formatted = number_format($amount, 0, ',', '.');
        
        return $showSymbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('formatIDRWithDecimals')) {
    /**
     * Format number to Indonesian Rupiah currency with decimal places
     * 
     * @param float|int $amount
     * @param int $decimals
     * @param bool $showSymbol
     * @return string
     */
    function formatIDRWithDecimals($amount, $decimals = 2, $showSymbol = true)
    {
        if ($amount === null || $amount === '') {
            return $showSymbol ? 'Rp 0' : '0';
        }
        
        $formatted = number_format($amount, $decimals, ',', '.');
        
        return $showSymbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('formatIDRShort')) {
    /**
     * Format large amounts with K, M, B suffixes
     * 
     * @param float|int $amount
     * @param bool $showSymbol
     * @return string
     */
    function formatIDRShort($amount, $showSymbol = true)
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
