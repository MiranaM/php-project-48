<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private const FIXTURE_PATH = __DIR__ . '/__fixtures__/';

    private function getFixtureFullPath(string $filename): string
    {
        return self::FIXTURE_PATH . $filename;
    }

    /**
     * @dataProvider formatProvider
     */
    public function testGenDiffWithVariousFormats(string $format, string $expectedFile): void
    {
        $file1 = $this->getFixtureFullPath('file1.json');
        $file2 = $this->getFixtureFullPath('file2.json');
        $expected = file_get_contents($this->getFixtureFullPath($expectedFile));

        $this->assertEquals($expected, genDiff($file1, $file2, $format));
    }

    public static function formatProvider(): array
    {
        return [
            ['stylish', 'expected_stylish.txt'],
            ['plain', 'expected_plain.txt'],
            ['json', 'expected_json.txt'],
        ];
    }
}
