<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\Json\formatJson;
use Differ\Exception\FormatException;

function format(array $diffTree, string $formatName): string
{
    return match ($formatName) {
        'stylish' => formatStylish($diffTree),
        'plain' => formatPlain($diffTree),
        'json' => formatJson($diffTree),
        default => throw new FormatException("Unknown format: {$formatName}")
    };
}
