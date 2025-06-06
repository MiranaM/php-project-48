<?php

namespace Differ\Formatters\Plain;

function formatPlain(array $diff): string
{
    $lines = array_filter(
        array_map(
            static fn(array $node): ?string => iter($node, ''),
            $diff
        ),
        static fn($x): bool => $x !== null
    );

    return implode("\n", $lines);
}

function iter(array $node, string $path): ?string
{
    $property = $path === '' ? $node['key'] : "{$path}.{$node['key']}";

    return match ($node['type']) {
        'nested' => buildNested($node['children'], $property),
        'added' => sprintf(
            "Property '%s' was added with value: %s",
            $property,
            toString($node['value'])
        ),
        'removed' => sprintf(
            "Property '%s' was removed",
            $property
        ),
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
    $lines = array_filter(
        array_map(
            static fn(array $child): ?string => iter($child, $path),
            $children
        ),
        static fn($x): bool => $x !== null
    );

    return implode("\n", $lines);
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
