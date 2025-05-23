<?php

namespace Differ\Formatters\Json;

function formatJson(array $data1, array $data2): string
{
    $diff = buildFlatDiff($data1, $data2);
    return json_encode($diff, JSON_PRETTY_PRINT);
}

function buildFlatDiff(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys);

    $diff = [];

    foreach ($keys as $key) {
        $has1 = array_key_exists($key, $data1);
        $has2 = array_key_exists($key, $data2);

        if ($has1 && !$has2) {
            $diff[] = [
                'key' => $key,
                'type' => 'removed',
                'value' => $data1[$key]
            ];
        } elseif (!$has1 && $has2) {
            $diff[] = [
                'key' => $key,
                'type' => 'added',
                'value' => $data2[$key]
            ];
        } elseif ($data1[$key] !== $data2[$key]) {
            $diff[] = [
                'key' => $key,
                'type' => 'changed',
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key],
            ];
        } else {
            $diff[] = [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $data1[$key]
            ];
        }
    }

    return $diff;
}
