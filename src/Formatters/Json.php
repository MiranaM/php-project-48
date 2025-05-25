<?php

namespace Differ\Formatters\Json;

function formatJson(array $tree): string
{
    return json_encode($tree, JSON_PRETTY_PRINT);
}
