<?php

namespace Differ\BuildDiff;

function buildDiff(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys);
    $diff = [];

    foreach ($keys as $key) {
        $oldValue = $data1[$key] ?? null;
        $newValue = $data2[$key] ?? null;

        if (!array_key_exists($key, $data1)) {
            $diff[] = ['key' => $key, 'type' => 'added', 'value' => $newValue];
        } elseif (!array_key_exists($key, $data2)) {
            $diff[] = ['key' => $key, 'type' => 'removed', 'value' => $oldValue];
        } elseif (is_array($oldValue) && is_array($newValue)) {
            $children = buildDiff($oldValue, $newValue);
            $diff[] = ['key' => $key, 'type' => 'nested', 'children' => $children];
        } elseif ($oldValue !== $newValue) {
            $diff[] = ['key' => $key, 'type' => 'changed', 'oldValue' => $oldValue, 'newValue' => $newValue];
        } else {
            $diff[] = ['key' => $key, 'type' => 'unchanged', 'value' => $oldValue];
        }
    }

    return $diff;
}
