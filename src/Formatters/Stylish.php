<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $data1, array $data2): string
{
    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($allKeys);
    $lines = ['{'];

    foreach ($allKeys as $key) {
        $has1 = array_key_exists($key, $data1);
        $has2 = array_key_exists($key, $data2);

        if ($has1 && !$has2) {
            $lines[] = "  - $key: " . var_export($data1[$key], true);
        } elseif (!$has1 && $has2) {
            $lines[] = "  + $key: " . var_export($data2[$key], true);
        } elseif ($data1[$key] === $data2[$key]) {
            $lines[] = "    $key: " . var_export($data1[$key], true);
        } else {
            $lines[] = "  - $key: " . var_export($data1[$key], true);
            $lines[] = "  + $key: " . var_export($data2[$key], true);
        }
    }

    $lines[] = '}';
    return implode("\n", $lines);
}
