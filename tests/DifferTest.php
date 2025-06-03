<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    const FIXTURE_PATH = __DIR__ . '/__fixtures__/';

    private string $file1;
    private string $file2;
    private string $expectedStylish;
    private string $expectedPlain;

    protected function setUp(): void
    {
        $this->file1 = self::FIXTURE_PATH . 'file1.json';
        $this->file2 = self::FIXTURE_PATH . 'file2.json';
        $this->expectedStylish = file_get_contents(self::FIXTURE_PATH . 'expected_stylish.txt');
        $this->expectedPlain = file_get_contents(self::FIXTURE_PATH . 'expected_plain.txt');
        $this->expectedJson = file_get_contents(self::FIXTURE_PATH . 'expected_json.txt');
    }

    public function testStylishFormat(): void
    {
        $this->assertEquals($this->expectedStylish, genDiff($this->file1, $this->file2, 'stylish'));
    }

    public function testPlainFormat(): void
    {
        $this->assertEquals($this->expectedPlain, genDiff($this->file1, $this->file2, 'plain'));
    }

    public function testJsonFormat(): void
    {
        $this->assertEquals($this->expectedJson, genDiff($this->file1, $this->file2, 'json'));
    }
}
