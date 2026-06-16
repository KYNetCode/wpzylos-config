<?php

defined('ABSPATH') || exit;

declare(strict_types=1);

namespace WPZylos\Framework\Config\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Config\EnvLoader;

/**
 * Tests for EnvLoader.
 */
class EnvLoaderTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/wpzylos-env-test-' . uniqid();
        mkdir($this->tmpDir, 0755, true);
    }

    protected function tearDown(): void
    {
        // Clean up temp files recursively
        if (is_dir($this->tmpDir)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->tmpDir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
            }
            rmdir($this->tmpDir);
        }

        // Clean up $_ENV entries that tests may have set
        foreach (['APP_NAME', 'DB_HOST', 'QUOTED', 'MY_KEY', 'A', 'B', 'VALID', 'T', 'F', 'N', 'E', 'TEST_APP_NAME'] as $key) {
            unset($_ENV[$key]);
        }
    }

    public function testLoadParsesEnvFile(): void
    {
        file_put_contents($this->tmpDir . '/.env', "APP_NAME=TestApp\nDB_HOST=localhost\n");

        $loader = new EnvLoader(false);
        $result = $loader->load($this->tmpDir . '/.env');

        $this->assertTrue($result);
        $this->assertSame('TestApp', $loader->get('APP_NAME'));
        $this->assertSame('localhost', $loader->get('DB_HOST'));
    }

    public function testLoadReturnsFalseForMissingFile(): void
    {
        $loader = new EnvLoader(false);
        $result = $loader->load($this->tmpDir . '/nonexistent.env');

        $this->assertFalse($result);
    }

    public function testGetReturnsDefaultForMissingKey(): void
    {
        $loader = new EnvLoader(false);
        $this->assertNull($loader->get('NONEXISTENT'));
        $this->assertSame('fallback', $loader->get('NONEXISTENT', 'fallback'));
    }

    public function testHasChecksKeyExistence(): void
    {
        file_put_contents($this->tmpDir . '/.env', "MY_KEY=my_value\n");

        $loader = new EnvLoader(false);
        $loader->load($this->tmpDir . '/.env');

        $this->assertTrue($loader->has('MY_KEY'));
        $this->assertFalse($loader->has('MISSING_KEY'));
    }

    public function testAllReturnsAllValues(): void
    {
        file_put_contents($this->tmpDir . '/.env', "A=1\nB=2\n");

        $loader = new EnvLoader(false);
        $loader->load($this->tmpDir . '/.env');

        $all = $loader->all();
        $this->assertArrayHasKey('A', $all);
        $this->assertArrayHasKey('B', $all);
        $this->assertSame('1', $all['A']);
        $this->assertSame('2', $all['B']);
    }

    public function testSkipsCommentsAndEmptyLines(): void
    {
        file_put_contents($this->tmpDir . '/.env', "# This is a comment\n\nVALID=yes\n");

        $loader = new EnvLoader(false);
        $loader->load($this->tmpDir . '/.env');

        $this->assertTrue($loader->has('VALID'));
        $this->assertCount(1, $loader->all());
    }

    public function testHandlesDoubleQuotedValues(): void
    {
        file_put_contents($this->tmpDir . '/.env', "QUOTED=\"hello world\"\n");

        $loader = new EnvLoader(false);
        $loader->load($this->tmpDir . '/.env');

        $this->assertSame('hello world', $loader->get('QUOTED'));
    }

    public function testHandlesSingleQuotedValues(): void
    {
        file_put_contents($this->tmpDir . '/.env', "QUOTED='hello world'\n");

        $loader = new EnvLoader(false);
        $loader->load($this->tmpDir . '/.env');

        $this->assertSame('hello world', $loader->get('QUOTED'));
    }

    public function testHandlesSpecialValues(): void
    {
        file_put_contents($this->tmpDir . '/.env', "T=true\nF=false\nN=null\nE=empty\n");

        $loader = new EnvLoader(false);
        $loader->load($this->tmpDir . '/.env');

        $this->assertSame('true', $loader->get('T'));
        $this->assertSame('false', $loader->get('F'));
        $this->assertSame('', $loader->get('N'));
        $this->assertSame('', $loader->get('E'));
    }

    public function testSetsEnvByDefault(): void
    {
        file_put_contents($this->tmpDir . '/.env', "TEST_APP_NAME=WPZylos\n");

        $loader = new EnvLoader(true);
        $loader->load($this->tmpDir . '/.env');

        $this->assertSame('WPZylos', $_ENV['TEST_APP_NAME']);
    }
}
