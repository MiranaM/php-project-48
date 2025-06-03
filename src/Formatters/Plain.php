<?php

namespace Differ\Formatters\Plain;

function formatPlain(array $diff): string
{
    $lines = [];

    foreach ($diff as $node) {
        $line = iter($node, '');
        if ($line !== null) {
            $lines[] = $line;
        }
    }

    return implode(PHP_EOL, $lines);
}

function iter(array $node, string $path): ?string
{
    $property = $path === '' ? $node['key'] : "{$path}.{$node['key']}";

    return match ($node['type']) {
        'nested' => buildNested($node['children'], $property),
        'added' => sprintf("Property '%s' was added with value: %s", $property, toString($node['value'])),
        'removed' => sprintf("Property '%s' was removed", $property),
        'changed' => sprintf(
            "Property '%s' was updated. From %s to %s",
            $property,
            toString($node['oldValue']),
            toString($node['newValue'])
        ),
        default => null,
    };
}

function buildNested(array $children, string $path): string
{
    $lines = [];

    foreach ($children as $child) {
        $line = iter($child, $path);
        if ($line !== null) {
            $lines[] = $line;
        }
    }

    return implode(PHP_EOL, $lines);
}

function toString(mixed $value): string
{
    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        is_null($value) => 'null',
        is_array($value) => '[complex value]',
        is_string($value) => "'{$value}'",
        default => (string) $value,
    };
}
