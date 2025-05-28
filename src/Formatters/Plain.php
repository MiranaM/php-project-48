<?php

namespace Differ\Formatters\Plain;

function toString(mixed $value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if ($value === null) {
        return 'null';
    }

    return is_string($value) ? "'{$value}'" : (string)$value;
}

function formatPlain(array $tree, string $ancestor = ''): string
{
    $lines = array_map(function ($node) use ($ancestor) {
        $property = $ancestor === '' ? $node['key'] : "{$ancestor}.{$node['key']}";

        switch ($node['type']) {
            case 'added':
                return "Property '{$property}' was added with value: " . toString($node['value']);
            case 'removed':
                return "Property '{$property}' was removed";
            case 'changed':
                return "Property '{$property}' was updated. From "
                    . toString($node['oldValue']) . " to " . toString($node['newValue']);
            case 'nested':
                return formatPlain($node['children'], $property);
            case 'unchanged':
                return '';
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $tree);

    return implode("\n", array_filter($lines, fn($line) => $line !== ''));
}
