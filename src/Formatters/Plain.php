<?php

namespace Differ\Formatters\Plain;

function formatValue($value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if ($value === null) {
        return 'null';
    }

    return is_string($value) ? "'$value'" : (string) $value;
}

function formatPlain(array $data1, array $data2, string $path = ''): string
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys);
    $lines = [];

    foreach ($keys as $key) {
        $fullPath = $path === '' ? $key : "$path.$key";
        $has1 = array_key_exists($key, $data1);
        $has2 = array_key_exists($key, $data2);

        if (!$has1 && $has2) {
            $value = formatValue($data2[$key]);
            $lines[] = "Property '$fullPath' was added with value: $value";
        } elseif ($has1 && !$has2) {
            $lines[] = "Property '$fullPath' was removed";
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            $lines[] = formatPlain($data1[$key], $data2[$key], $fullPath);
        } elseif ($data1[$key] !== $data2[$key]) {
            $old = formatValue($data1[$key]);
            $new = formatValue($data2[$key]);
            $lines[] = "Property '$fullPath' was updated. From $old to $new";
        }
    }

    return implode("\n", array_filter($lines));
}
