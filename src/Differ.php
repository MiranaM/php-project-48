<?php

namespace Differ\Differ;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $data1 = getParsedJson($pathToFile1);
    $data2 = getParsedJson($pathToFile2);

    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($allKeys);

    $lines = array_map(
        function ($key) use ($data1, $data2) {
            $hasKey1 = array_key_exists($key, $data1);
            $hasKey2 = array_key_exists($key, $data2);

            $val1 = $hasKey1 ? varToStr($data1[$key]) : null;
            $val2 = $hasKey2 ? varToStr($data2[$key]) : null;

            if ($hasKey1 && !$hasKey2) {
                return "  - {$key}: {$val1}";
            }
            if (!$hasKey1 && $hasKey2) {
                return "  + {$key}: {$val2}";
            }
            if ($val1 !== $val2) {
                return "  - {$key}: {$val1}\n  + {$key}: {$val2}";
            }
            return "    {$key}: {$val1}";
        },
        $allKeys
    );

    return "{\n" . implode("\n", $lines) . "\n}";
}

function getParsedJson(string $path): array
{
    $content = file_get_contents($path);
    return json_decode($content, true); // true → ассоциативный массив
}

function varToStr($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value === null) {
        return 'null';
    }
    return (string)$value;
}
