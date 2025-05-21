<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $tree, int $depth = 1): string
{
    $indent = fn($lvl) => str_repeat('    ', $lvl);
    $lines = [];

    foreach ($tree as $node) {
        $key = $node['key'];
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                $children = formatStylish($node['children'], $depth + 1);
                $lines[] = "{$indent($depth)}    {$key}: {$children}";
                break;
            case 'added':
                $val = toString($node['value'], $depth + 1);
                $lines[] = "{$indent($depth)}  + {$key}: {$val}";
                break;
            case 'removed':
                $val = toString($node['value'], $depth + 1);
                $lines[] = "{$indent($depth)}  - {$key}: {$val}";
                break;
            case 'changed':
                $old = toString($node['oldValue'], $depth + 1);
                $new = toString($node['newValue'], $depth + 1);
                $lines[] = "{$indent($depth)}  - {$key}: {$old}";
                $lines[] = "{$indent($depth)}  + {$key}: {$new}";
                break;
            case 'unchanged':
                $val = toString($node['value'], $depth + 1);
                $lines[] = "{$indent($depth)}    {$key}: {$val}";
                break;
        }
    }

    return "{\n" . implode("\n", $lines) . "\n{$indent($depth - 1)}}";
}

function toString($value, int $depth): string
{
    if (!is_array($value)) {
        return var_export($value, true);
    }

    $indent = str_repeat('    ', $depth);
    $bracketIndent = str_repeat('    ', $depth - 1);
    $lines = [];

    foreach ($value as $key => $val) {
        $valStr = toString($val, $depth + 1);
        $lines[] = "{$indent}{$key}: {$valStr}";
    }

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}
