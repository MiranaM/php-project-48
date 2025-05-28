<?php

namespace Differ\BuildDiff;

use function Functional\sort;

function buildDiff(array $first, array $second): array
{
    $uniqueKeys = array_unique(array_merge(array_keys($first), array_keys($second)));
    $sortedKeys = sort($uniqueKeys, fn($a, $b) => $a <=> $b);

    return array_map(function ($key) use ($first, $second) {
        $valueFirst = $first[$key] ?? null;
        $valueSecond = $second[$key] ?? null;

        if (
            is_array($valueFirst) && !array_is_list($valueFirst) &&
            is_array($valueSecond) && !array_is_list($valueSecond)
        ) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => buildDiff($valueFirst, $valueSecond),
            ];
        }

        if (!array_key_exists($key, $first)) {
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $valueSecond,
            ];
        }

        if (!array_key_exists($key, $second)) {
            return [
                'key' => $key,
                'type' => 'removed',
                'value' => $valueFirst,
            ];
        }

        if ($valueFirst === $valueSecond) {
            return [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $valueFirst,
            ];
        }

        return [
            'key' => $key,
            'type' => 'changed',
            'oldValue' => $valueFirst,
            'newValue' => $valueSecond,
        ];
    }, $sortedKeys);
}
