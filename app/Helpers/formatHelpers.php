<?php

if (!function_exists('formatCount')) {
    function formatCount($count, $step = 50)
    {
        // Calculate the nearest upper boundary based on the step
        if ($count <= $step) {
            return $count;
        }

        $upperBound = ceil($count / $step) * $step;

        return $upperBound . '+';
    }
}

if (!function_exists('faviconUrl')) {
    function faviconUrl(?array $site = null): string
    {
        if (!empty($site['site_logo'])) {
            $path = storage_path('app/public/' . $site['site_logo']);
            $v = file_exists($path) ? filemtime($path) : 1;
            return asset('storage/' . $site['site_logo']) . '?v=' . $v;
        }

        $fallback = public_path('favicon-childsee.png');
        $v = file_exists($fallback) ? filemtime($fallback) : 1;
        return asset('favicon-childsee.png') . '?v=' . $v;
    }
}
