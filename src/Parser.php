<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $filepath): array
{
    $content = file_get_contents($filepath);
    $ext = pathinfo($filepath, PATHINFO_EXTENSION);

    return match ($ext) {
        'json' => json_decode($content, true),
        'yml', 'yaml' => Yaml::parse($content),
        default => throw new \Exception("Unsupported file extension: $ext"),
    };
}
