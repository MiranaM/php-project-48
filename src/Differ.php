<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\BuildDiff\buildDiff;
use function Differ\Formatters\format;

function safeFileGetContents(string $path): string
{
    $content = file_get_contents($path);
    if ($content === false) {
        throw new \RuntimeException("Can't read file: {$path}");
    }
    return $content;
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $content1 = safeFileGetContents($pathToFile1);
    $content2 = safeFileGetContents($pathToFile2);

    $extension1 = pathinfo($pathToFile1, PATHINFO_EXTENSION);
    $extension2 = pathinfo($pathToFile2, PATHINFO_EXTENSION);

    $data1 = parse($content1, $extension1);
    $data2 = parse($content2, $extension2);

    $diff = buildDiff($data1, $data2);

    return format($diff, $format);
}
