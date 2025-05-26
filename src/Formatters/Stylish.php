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
    $currentIndent = str_repeat(' ', $indentSize * ($depth - 1));
    $bracketIndent = str_repeat(' ', $indentSize * ($depth - 1));

    $lines = array_map(function ($node) use ($depth, $currentIndent, $bracketIndent) {
        $key = $node['key'];
        switch ($node['type']) {
            case 'added':
                return $currentIndent . "  + " . $key . ": " . stringify($node['value'], $depth + 1);
            case 'removed':
                return $currentIndent . "  - " . $key . ": " . stringify($node['value'], $depth + 1);
            case 'changed':
                return $currentIndent . "  - " . $key . ": " . stringify($node['oldValue'], $depth + 1) . "\n" .
                       $currentIndent . "  + " . $key . ": " . stringify($node['newValue'], $depth + 1);
            case 'nested':
                $children = formatStylish($node['children'], $depth + 1);
                return $currentIndent . "    " . $key . ": " . $children;
            case 'unchanged':
                return $currentIndent . "    " . $key . ": " . stringify($node['value'], $depth + 1);
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $tree);

    return "{\n" . implode("\n", $lines) . "\n" . $bracketIndent . "}";
}
