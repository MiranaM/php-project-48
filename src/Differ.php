<?php

namespace Differ\Differ;

use function Differ\Parser\parseFile;
use function Differ\Formatters\Stylish\formatStylish;

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $data1 = parseFile($path1);
    $data2 = parseFile($path2);
    $diff = buildDiff($data1, $data2);

    return formatStylish($diff);
}

function buildDiff(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys);
    $result = [];

    foreach ($keys as $key) {
        $value1 = $data1[$key] ?? null;
        $value2 = $data2[$key] ?? null;

        if (!array_key_exists($key, $data1)) {
            $result[] = ['type' => 'added', 'key' => $key, 'value' => $value2];
        } elseif (!array_key_exists($key, $data2)) {
            $result[] = ['type' => 'removed', 'key' => $key, 'value' => $value1];
        } elseif (is_array($value1) && is_array($value2)) {
            $result[] = ['type' => 'nested', 'key' => $key, 'children' => buildDiff($value1, $value2)];
        } elseif ($value1 !== $value2) {
            $result[] = ['type' => 'changed', 'key' => $key, 'oldValue' => $value1, 'newValue' => $value2];
        } else {
            $result[] = ['type' => 'unchanged', 'key' => $key, 'value' => $value1];
        }
    }

    return $result;
}
