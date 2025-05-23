<?php

namespace Differ\Differ;

use function Differ\Parser\parseFile;
use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Json\formatJson;
use Differ\Exception\FormatException;

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $data1 = parseFile($path1);
    $data2 = parseFile($path2);

    return match ($format) {
        'plain' => formatPlain($data1, $data2, ''),
        'stylish' => formatStylish($data1, $data2),
        'json' => formatJson($data1, $data2),
        default => throw new FormatException("Unknown format: {$formatName}"),
    };
}
