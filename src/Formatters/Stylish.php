<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $diff): string
{
    return iter($diff);
}

function iter(array $tree, int $depth = 1): string
{
    $indent = str_repeat('    ', $depth);
    $currentIndent = str_repeat('    ', $depth - 1);
    $lines = [];

    foreach ($tree as $node) {
        $key = $node['key'];

        switch ($node['type']) {
            case 'nested':
                $children = iter($node['children'], $depth + 1);
                $lines[] = "{$indent}{$key}: {$children}";
                break;

            case 'added':
                $value = toString($node['value'], $depth + 1);
                $lines[] = "{$currentIndent}  + {$key}: {$value}";
                break;

            case 'removed':
                $value = toString($node['value'], $depth + 1);
                $lines[] = "{$currentIndent}  - {$key}: {$value}";
                break;

            case 'changed':
                $oldValue = toString($node['oldValue'], $depth + 1);
                $newValue = toString($node['newValue'], $depth + 1);
                $lines[] = "{$currentIndent}  - {$key}: {$oldValue}";
                $lines[] = "{$currentIndent}  + {$key}: {$newValue}";
                break;

            case 'unchanged':
                $value = toString($node['value'], $depth + 1);
                $lines[] = "{$indent}{$key}: {$value}";
                break;
        }
    }

    $result = implode("\n", $lines);
    return "{\n{$result}\n{$currentIndent}}";
}

function toString(mixed $value, int $depth): string
{
    if (is_array($value)) {
        $indent = str_repeat('    ', $depth);
        $bracketIndent = str_repeat('    ', $depth - 1);

        $lines = [];
        foreach ($value as $key => $val) {
            $lines[] = "{$indent}{$key}: " . toString($val, $depth + 1);
        }

        $result = implode("\n", $lines);
        return "{\n{$result}\n{$bracketIndent}}";
    }

    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        is_null($value) => 'null',
        default => (string) $value,
    };
}
