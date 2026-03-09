<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Generate a palette based on a primary hex color.
     */
    public static function generatePalette($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $rgb = self::hexToRgb($hex);
        $hsl = self::rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);

        return [
            'primary' => '#' . $hex,
            'primary_rgb' => "{$rgb['r']}, {$rgb['g']}, {$rgb['b']}",
            'light' => self::hslToHex($hsl['h'], $hsl['s'], min(95, $hsl['l'] + 40)),
            'dark' => self::hslToHex($hsl['h'], $hsl['s'], max(10, $hsl['l'] - 20)),
            'deep' => self::hslToHex($hsl['h'], max(10, $hsl['s'] - 10), 10),
            'accent' => self::hslToHex($hsl['h'], $hsl['s'], min(90, $hsl['l'] + 15)),
            'accent_contrast' => self::getContrastText(self::hexToRgb(self::hslToHex($hsl['h'], max(10, $hsl['s'] - 10), 10))), // Text for deep background
            'safe_accent' => self::hslToHex($hsl['h'], $hsl['s'], max(70, min(90, $hsl['l'] + 30))), // Light color safe for dark bg
            'safe_primary' => self::hslToHex($hsl['h'], $hsl['s'], max(20, min(45, $hsl['l'] - 10))), // Dark color safe for light bg
            'contrast_text' => self::getContrastText($rgb),
        ];
    }

    private static function hexToRgb($hex)
    {
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    private static function rgbToHsl($r, $g, $b)
    {
        $r /= 255; $g /= 255; $b /= 255;
        $max = max($r, $g, $b); $min = min($r, $g, $b);
        $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = $s = 0;
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            switch ($max) {
                case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                case $g: $h = ($b - $r) / $d + 2; break;
                case $b: $h = ($r - $g) / $d + 4; break;
            }
            $h /= 6;
        }
        return ['h' => $h * 360, 's' => $s * 100, 'l' => $l * 100];
    }

    private static function hslToHex($h, $s, $l)
    {
        $h /= 360; $s /= 100; $l /= 100;
        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = self::hueToRgb($p, $q, $h + 1/3);
            $g = self::hueToRgb($p, $q, $h);
            $b = self::hueToRgb($p, $q, $h - 1/3);
        }
        return sprintf("#%02x%02x%02x", round($r * 255), round($g * 255), round($b * 255));
    }

    private static function hueToRgb($p, $q, $t)
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }

    private static function getContrastText($rgb)
    {
        // YIQ formula
        $yiq = (($rgb['r'] * 299) + ($rgb['g'] * 587) + ($rgb['b'] * 114)) / 1000;
        return ($yiq >= 128) ? '#000000' : '#ffffff';
    }
}
