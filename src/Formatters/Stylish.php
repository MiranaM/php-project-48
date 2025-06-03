<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $diff): string
{
    return iter($diff);
}

function iter(array $tree, int $depth = 1): string
{
    $indentSize = 4;
    $currentIndent = str_repeat(' ', $depth * $indentSize - 2);
    $bracketIndent = str_repeat(' ', ($depth - 1) * $indentSize);

    $lines = array_map(
        fn($node) => formatNode($node, $depth, $currentIndent),
        $tree
    );

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}

function formatNode(array $node, int $depth, string $indent): string
{
    $key = $node['key'];

    return match ($node['type']) {
        'nested' => sprintf(
            "%s  %s: %s",
            $indent,
            $key,
            iter($node['children'], $depth + 1)
        ),
        'added' => sprintf(
            "%s+ %s: %s",
            $indent,
            $key,
            toString($node['value'], $depth + 1)
        ),
        'removed' => sprintf(
            "%s- %s: %s",
            $indent,
            $key,
            toString($node['value'], $depth + 1)
        ),
        'changed' => implode("\n", [
            sprintf(
                "%s- %s: %s",
                $indent,
                $key,
                toString($node['oldValue'], $depth + 1)
            ),
            sprintf(
                "%s+ %s: %s",
                $indent,
                $key,
                toString($node['newValue'], $depth + 1)
            ),
        ]),
        'unchanged' => sprintf(
            "%s  %s: %s",
            $indent,
            $key,
            toString($node['value'], $depth + 1)
        ),
        default => '',
    };
}

function toString(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return match (true) {
            is_bool($value) => $value ? 'true' : 'false',
            is_null($value) => 'null',
            default => (string) $value,
        };
    }

    $indentSize = 4;
    $indent = str_repeat(' ', $depth * $indentSize);
    $bracketIndent = str_repeat(' ', ($depth - 1) * $indentSize);

    $lines = array_map(
        fn($key) => sprintf(
            "%s%s: %s",
            $indent,
            $key,
            toString($value[$key], $depth + 1)
        ),
        array_keys($value)
    );

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}
