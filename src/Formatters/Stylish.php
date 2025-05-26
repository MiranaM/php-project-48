<?php

namespace Differ\Formatters\Stylish;

function stringify(mixed $value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if ($value === null) {
        return 'null';
    }

    if (!is_array($value)) {
        return (string) $value;
    }

    $indentSize = 4;
    $currentIndent = str_repeat(' ', ($depth + 1) * $indentSize);
    $bracketIndent = str_repeat(' ', $depth * $indentSize);
    $lines = [];

    foreach ($value as $key => $val) {
        $lines[] = "{$currentIndent}{$key}: " . stringify($val, $depth + 1);
    }

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}

function formatStylish(array $tree, int $depth = 0): string
{
    $indentSize = 4;
    $baseIndent = str_repeat(' ', $depth * $indentSize);
    $lines = [];

    foreach ($tree as $node) {
        $key = $node['key'];
        $valueIndent = str_repeat(' ', $indentSize * $depth + 2); // '  '
        $signIndent = fn($sign) => str_repeat(' ', $indentSize * $depth) . "$sign ";

        switch ($node['type']) {
            case 'added':
                $lines[] = $signIndent('+') . "{$key}: " . stringify($node['value'], $depth + 1);
                break;
            case 'removed':
                $lines[] = $signIndent('-') . "{$key}: " . stringify($node['value'], $depth + 1);
                break;
            case 'unchanged':
                $lines[] = $valueIndent . "  {$key}: " . stringify($node['value'], $depth + 1);
                break;
            case 'updated':
                $lines[] = $signIndent('-') . "{$key}: " . stringify($node['oldValue'], $depth + 1);
                $lines[] = $signIndent('+') . "{$key}: " . stringify($node['newValue'], $depth + 1);
                break;
            case 'nested':
                $children = formatStylish($node['children'], $depth + 1);
                $lines[] = $valueIndent . "  {$key}: {$children}";
                break;
        }
    }

    return "{\n" . implode("\n", $lines) . "\n{$baseIndent}}";
}
