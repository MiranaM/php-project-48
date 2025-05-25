<?php

namespace Differ\Formatters\Plain;

function formatValue($value): string
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

    return is_string($value) ? "'$value'" : (string)$value;
}

function formatPlain(array $tree, string $path = ''): string
{
    $lines = [];

    foreach ($tree as $node) {
        $key = $node['key'];
        $property = $path === '' ? $key : "$path.$key";

        switch ($node['type']) {
            case 'added':
                $lines[] = "Property '$property' was added with value: " . formatValue($node['value']);
                break;
            case 'removed':
                $lines[] = "Property '$property' was removed";
                break;
            case 'updated':
                $old = formatValue($node['oldValue']);
                $new = formatValue($node['newValue']);
                $lines[] = "Property '$property' was updated. From $old to $new";
                break;
            case 'nested':
                $lines[] = formatPlain($node['children'], $property);
                break;
        }
    }

    return implode("\n", array_filter($lines));
}
