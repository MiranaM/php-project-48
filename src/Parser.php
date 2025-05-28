<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $filepath): array
{
    $content = file_get_contents($filepath);
    if ($content === false) {
        throw new \RuntimeException("Cannot read file: {$filepath}");
    }

    $ext = pathinfo($filepath, PATHINFO_EXTENSION);

    return match ($ext) {
        'json' => json_decode($content, true, flags: JSON_THROW_ON_ERROR),
        'yml', 'yaml' => Yaml::parse($content),
        default => throw new \InvalidArgumentException("Unsupported file extension: $ext"),
    };
}
