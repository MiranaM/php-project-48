<?php

namespace Differ\BuildDiff;

function buildDiff(array $data1, array $data2): array
{
    $keys = array_keys(array_merge($data1, $data2));
    $uniqueKeys = array_unique($keys);
    sort($uniqueKeys);

    $diff = array_map(function ($key) use ($data1, $data2) {
        $oldValue = $data1[$key] ?? null;
        $newValue = $data2[$key] ?? null;

        if (!array_key_exists($key, $data1)) {
            return ['key' => $key, 'type' => 'added', 'value' => $newValue];
        }

        if (!array_key_exists($key, $data2)) {
            return ['key' => $key, 'type' => 'removed', 'value' => $oldValue];
        }

        if (is_array($oldValue) && is_array($newValue)) {
            return ['key' => $key, 'type' => 'nested', 'children' => buildDiff($oldValue, $newValue)];
        }

        if ($oldValue !== $newValue) {
            return ['key' => $key, 'type' => 'changed', 'oldValue' => $oldValue, 'newValue' => $newValue];
        }

        return ['key' => $key, 'type' => 'unchanged', 'value' => $oldValue];
    }, $uniqueKeys);

    return $diff;
}
