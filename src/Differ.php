<?php

namespace Differ\Differ;

use function Differ\Parser\parseFile;
use function Differ\BuildDiff\buildDiff;
use function Differ\Formatters\format;

function genDiff(string $path1, string $path2, string $formatName = 'stylish'): string
{
    $data1 = parseFile($path1);
    $data2 = parseFile($path2);
    $diffTree = buildDiff($data1, $data2);

    return format($diffTree, $formatName);
}
