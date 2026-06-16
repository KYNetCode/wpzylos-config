<?php

defined('ABSPATH') || exit;

declare(strict_types=1);

namespace WPZylos\Framework\Config\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Config\ConfigServiceProvider;

/**
 * Tests for ConfigServiceProvider.
 */
class ConfigServiceProviderTest extends TestCase
{
    public function testProviderIsInstantiable(): void
    {
        $provider = new ConfigServiceProvider();
        $this->assertInstanceOf(ConfigServiceProvider::class, $provider);
    }
}
