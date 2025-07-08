<?php

if (!function_exists('app_timezone')) {
    /**
     * Get the application timezone
     */
    function app_timezone()
    {
        return config('app.timezone', 'Asia/Jakarta');
    }
}

if (!function_exists('now_local')) {
    /**
     * Get current date/time in application timezone
     */
    function now_local($format = null)
    {
        $now = \Carbon\Carbon::now(app_timezone());
        return $format ? $now->format($format) : $now;
    }
}

if (!function_exists('to_local_timezone')) {
    /**
     * Convert any datetime to local timezone
     */
    function to_local_timezone($datetime, $format = null)
    {
        if (!$datetime) return null;
        
        $carbon = \Carbon\Carbon::parse($datetime)->setTimezone(app_timezone());
        return $format ? $carbon->format($format) : $carbon;
    }
}

if (!function_exists('local_time_format')) {
    /**
     * Format time in local timezone with common Indonesian format
     */
    function local_time_format($datetime, $includeSeconds = false)
    {
        if (!$datetime) return '-';
        
        $format = $includeSeconds ? 'd M Y H:i:s' : 'd M Y H:i';
        $timezone = app_timezone();
        
        // Determine timezone suffix based on Indonesia timezone
        $suffix = 'WIB'; // Default
        if (str_contains($timezone, 'Makassar')) {
            $suffix = 'WITA';
        } elseif (str_contains($timezone, 'Jayapura')) {
            $suffix = 'WIT';
        }
        
        return to_local_timezone($datetime, $format) . ' ' . $suffix;
    }
}

if (!function_exists('server_time_info')) {
    /**
     * Get server time information
     */
    function server_time_info()
    {
        return [
            'server_timezone' => date_default_timezone_get(),
            'app_timezone' => app_timezone(),
            'server_time' => now()->format('Y-m-d H:i:s T'),
            'local_time' => now_local('Y-m-d H:i:s T'),
            'formatted_local' => local_time_format(now()),
        ];
    }
}
