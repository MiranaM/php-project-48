<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\BuildDiff\buildDiff;
use function Differ\Formatters\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $content1 = file_get_contents($pathToFile1);
    $content2 = file_get_contents($pathToFile2);

    $extension1 = pathinfo($pathToFile1, PATHINFO_EXTENSION);
    $extension2 = pathinfo($pathToFile2, PATHINFO_EXTENSION);

    $data1 = parse($content1, $extension1);
    $data2 = parse($content2, $extension2);

    $diff = buildDiff($data1, $data2);

    return format($diff, $format);
}
