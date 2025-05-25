<?php

namespace Differ\Formatters\Stylish;

function stringify($value, int $depth): string
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

    $indent = str_repeat('    ', $depth + 1);
    $bracketIndent = str_repeat('    ', $depth);
    $lines = [];

    foreach ($value as $key => $val) {
        $lines[] = "$indent$key: " . stringify($val, $depth + 1);
    }

    return "{\n" . implode("\n", $lines) . "\n$bracketIndent}";
}

function formatStylish(array $tree, int $depth = 0): string
{
    $baseIndent = str_repeat('    ', $depth);
    $markIndent = fn($mark) => substr($baseIndent, 0, -2) . "  $mark ";

    $lines = [];

    foreach ($tree as $node) {
        $key = $node['key'];

        switch ($node['type']) {
            case 'added':
                $value = stringify($node['value'], $depth);
                $lines[] = "{$markIndent('+')}$key: $value";
                break;
            case 'removed':
                $value = stringify($node['value'], $depth);
                $lines[] = "{$markIndent('-')}$key: $value";
                break;
            case 'unchanged':
                $value = stringify($node['value'], $depth);
                $lines[] = "{$markIndent(' ')}$key: $value";
                break;
            case 'changed':
                $old = stringify($node['oldValue'], $depth);
                $new = stringify($node['newValue'], $depth);
                $lines[] = "{$markIndent('-')}$key: $old";
                $lines[] = "{$markIndent('+')}$key: $new";
                break;
            case 'nested':
                $children = formatStylish($node['children'], $depth + 1);
                $lines[] = "{$markIndent(' ')}$key: $children";
                break;
        }
    }

    return "{\n" . implode("\n", $lines) . "\n{$baseIndent}}";
}
