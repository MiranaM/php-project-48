<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $format): array
{
    return match (strtolower($format)) {
        'json' => json_decode($content, true),
        'yml', 'yaml' => Yaml::parse($content),
        default => throw new \Exception("Unsupported format: {$format}"),
    };
}
