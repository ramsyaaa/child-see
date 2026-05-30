<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewayConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'offline_enabled',
        'mayar_enabled',
    ];

    protected $casts = [
        'offline_enabled' => 'boolean',
        'mayar_enabled' => 'boolean',
    ];

    /**
     * Get the singleton config instance
     */
    public static function getConfig()
    {
        return self::first() ?? self::create([
            'offline_enabled' => true,
            'mayar_enabled' => false,
        ]);
    }

    /**
     * Get enabled gateways
     */
    public static function getEnabledGateways()
    {
        $config = self::getConfig();
        $gateways = [];

        if ($config->offline_enabled) {
            $gateways[] = 'offline';
        }

        if ($config->mayar_enabled) {
            $gateways[] = 'mayar';
        }

        return $gateways;
    }
}

