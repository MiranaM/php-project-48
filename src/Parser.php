<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $path): array
{
    $content = file_get_contents($path);
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    return match (strtolower($ext)) {
        'json' => json_decode($content, true),
        'yml', 'yaml' => json_decode(json_encode(Yaml::parse($content)), true), // <== Вот это ключ
        default => throw new \Exception("Unsupported file format: {$ext}"),
    };
}
