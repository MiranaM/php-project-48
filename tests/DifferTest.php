<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $file1;
    private string $file2;
    private string $expectedStylish;
    private string $expectedPlain;

    protected function setUp(): void
    {
        $this->file1 = __DIR__ . '/__fixtures__/file1.json';
        $this->file2 = __DIR__ . '/__fixtures__/file2.json';
        $this->expectedStylish = file_get_contents(__DIR__ . '/__fixtures__/expected_stylish.txt');
        $this->expectedPlain = file_get_contents(__DIR__ . '/__fixtures__/expected_plain.txt');
    }

    public function testStylishFormat(): void
    {
        $this->assertEquals($this->expectedStylish, genDiff($this->file1, $this->file2, 'stylish'));
    }

    public function testPlainFormat(): void
    {
        $this->assertEquals($this->expectedPlain, genDiff($this->file1, $this->file2, 'plain'));
    }
}
