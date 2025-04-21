<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        $this->fixturesPath = __DIR__ . '/fixtures/';
    }

    public function testFlatJsonDiff()
    {
        $file1 = $this->fixturesPath . 'file1.json';
        $file2 = $this->fixturesPath . 'file2.json';
        $expected = <<<EOD
{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
EOD;

        $this->assertSame($expected, genDiff($file1, $file2));
    }
}
