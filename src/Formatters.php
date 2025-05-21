<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;

function format(array $diffTree, string $formatName): string
{
    return match ($formatName) {
        'stylish' => formatStylish($diffTree),
        'plain' => formatPlain($diffTree),
        default => throw new \Exception("Unknown format: {$formatName}")
    };
}
