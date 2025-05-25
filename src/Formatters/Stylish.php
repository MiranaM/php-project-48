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
    $indent = str_repeat('    ', $depth);
    $lines = [];

    foreach ($tree as $node) {
        $key = $node['key'];

        switch ($node['type']) {
            case 'added':
                $value = stringify($node['value'], $depth);
                $lines[] = "{$indent}  + $key: $value";
                break;
            case 'removed':
                $value = stringify($node['value'], $depth);
                $lines[] = "{$indent}  - $key: $value";
                break;
            case 'unchanged':
                $value = stringify($node['value'], $depth);
                $lines[] = "{$indent}    $key: $value";
                break;
            case 'updated':
                $old = stringify($node['oldValue'], $depth);
                $new = stringify($node['newValue'], $depth);
                $lines[] = "{$indent}  - $key: $old";
                $lines[] = "{$indent}  + $key: $new";
                break;
            case 'nested':
                $children = formatStylish($node['children'], $depth + 1);
                $lines[] = "{$indent}    $key: $children";
                break;
        }
    }

    return "{\n" . implode("\n", $lines) . "\n$indent}";
}
