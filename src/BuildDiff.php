<?php

namespace Differ\BuildDiff;

use Illuminate\Support\Collection;
use function Illuminate\Support\collect;

function buildDiff(array $data1, array $data2): array
{
    return collect(array_merge(array_keys($data1), array_keys($data2)))
        ->unique()
        ->sort()
        ->values()
        ->map(function ($key) use ($data1, $data2) {
            $oldValue = $data1[$key] ?? null;
            $newValue = $data2[$key] ?? null;

            return match (true) {
                !array_key_exists($key, $data1) => ['key' => $key, 'type' => 'added', 'value' => $newValue],
                !array_key_exists($key, $data2) => ['key' => $key, 'type' => 'removed', 'value' => $oldValue],
                is_array($oldValue) && is_array($newValue) => [
                    'key' => $key,
                    'type' => 'nested',
                    'children' => buildDiff($oldValue, $newValue),
                ],
                $oldValue !== $newValue => [
                    'key' => $key,
                    'type' => 'changed',
                    'oldValue' => $oldValue,
                    'newValue' => $newValue,
                ],
                default => ['key' => $key, 'type' => 'unchanged', 'value' => $oldValue],
            };
        })
        ->all();
}
