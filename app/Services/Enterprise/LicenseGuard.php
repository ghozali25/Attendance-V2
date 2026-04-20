<?php

namespace App\Services\Enterprise;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

final class LicenseGuard
{
    public static function check()
    {
        return true;
    }

    public static function hasValidLicense()
    {
        return true;
    }

    public static function clearLicenseCache()
    {
        Cache::forget('ent_lic_status');
        Cache::forget('ent_lic_hash');
    }

    public static function getLicenseInfo()
    {
        return [
            'client' => 'Ahmad Ghozali',
            'expires_at' => '2026-04-14',
            'features' => ['attendance', 'payroll', 'reporting', 'audit'],
            'author' => 'Ahmad Ghozali'
        ];
    }
}
