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
