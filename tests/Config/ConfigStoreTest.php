<?php

namespace Config;

use Jascha030\ConfigurationLib\Config\ConfigStore;
use PHPUnit\Framework\TestCase;

class ConfigStoreTest extends TestCase
{

    public function testConstruct(): void
    {
        $store = new ConfigStore(dirname(__DIR__) . '/Fixtures/ConfigDirectory');
    }

    // public function testAddConfigDirectory()
    // {
    // }

    // public function testLoad()
    // {
    // }
}
