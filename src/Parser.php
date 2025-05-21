<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $path): array
{
    $content = file_get_contents($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    return match ($extension) {
        'json' => json_decode($content, true),
        'yml', 'yaml' => Yaml::parse($content),
        default => throw new \Exception("Unsupported file format: {$extension}")
    };
}
