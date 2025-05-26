<?php

namespace Differ\Formatters\Stylish;

function stringify(mixed $value, int $depth): string
{
    $indentSize = 4;
    $currentIndent = str_repeat(' ', $indentSize * $depth);
    $bracketIndent = str_repeat(' ', $indentSize * ($depth - 1));

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if ($value === null) {
        return 'null';
    }

    if (!is_array($value)) {
        return (string) $value;
    }

    $lines = array_map(
        fn($key) => $currentIndent . $key . ': ' . stringify($value[$key], $depth + 1),
        array_keys($value)
    );

    return "{\n" . implode("\n", $lines) . "\n" . $bracketIndent . "}";
}

function formatStylish(array $tree, int $depth = 1): string
{
    $indentSize = 4;
    $currentIndent = str_repeat(' ', $indentSize * $depth - 2);
    $bracketIndent = str_repeat(' ', $indentSize * ($depth - 1));

    $lines = [];

    foreach ($tree as $node) {
        $key = $node['key'];
        switch ($node['type']) {
            case 'added':
                $lines[] = "{$currentIndent}+ {$key}: " . stringify($node['value'], $depth + 1);
                break;
            case 'removed':
                $lines[] = "{$currentIndent}- {$key}: " . stringify($node['value'], $depth + 1);
                break;
            case 'unchanged':
                $lines[] = "{$bracketIndent}    {$key}: " . stringify($node['value'], $depth + 1);
                break;
            case 'updated':
                $lines[] = "{$currentIndent}- {$key}: " . stringify($node['oldValue'], $depth + 1);
                $lines[] = "{$currentIndent}+ {$key}: " . stringify($node['newValue'], $depth + 1);
                break;
            case 'nested':
                $children = formatStylish($node['children'], $depth + 1);
                $lines[] = "{$bracketIndent}    {$key}: {$children}";
                break;
        }
    }

    return "{\n" . implode("\n", $lines) . "\n" . $bracketIndent . "}";
}
