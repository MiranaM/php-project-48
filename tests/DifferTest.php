<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $file1;
    private string $file2;
    private string $expected;

    protected function setUp(): void
    {
        $this->file1 = __DIR__ . '/__fixtures__/file1.json';
        $this->file2 = __DIR__ . '/__fixtures__/file2.json';
        $this->expected = file_get_contents(__DIR__ . '/__fixtures__/expected_stylish.txt');
    }

    public function testStylishFormat(): void
    {
        $this->assertEquals($this->expected, genDiff($this->file1, $this->file2));
    }
}
