<?php

namespace Config;

use Jascha030\ConfigurationLib\Config\ConfigStore;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class ConfigStoreTest extends TestCase
{
    private const NON_EXISTING_DIRECTORY = __DIR__ . '/doesNotExist';

    public function testConstruct(): ConfigStore
    {
        $store = new ConfigStore(dirname(__DIR__) . '/Fixtures/ConfigDirectory');
        self::assertInstanceOf(ConfigStore::class, $store);

        return $store;
    }

    public function testInvalidDirectoryExceptionIsThrown(): void
    {
        $directory = self::NON_EXISTING_DIRECTORY;
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Invalid configuration directory: \"{$directory}\"");

        new ConfigStore(self::NON_EXISTING_DIRECTORY);
    }

    /**
     * @depends testConstruct
     *
     * @param \Jascha030\ConfigurationLib\Config\ConfigStore $store
     */
    public function testInvalidAddedDirectoryExceptionIsThrown(ConfigStore $store): void
    {
        $this->expectException(\RuntimeException::class);
        $store->addConfigDirectory(self::NON_EXISTING_DIRECTORY);
    }

    /**
     * @depends testConstruct
     *
     * @param \Jascha030\ConfigurationLib\Config\ConfigStore $store
     */
    public function testAddConfigDirectory(ConfigStore $store): void
    {
        $store->addConfigDirectory(dirname(__DIR__) . '/Fixtures/ExtraConfigDirectory');
    }

    /**
     * @noinspection UnnecessaryAssertionInspection
     */
    public function testCreateFinder(ConfigStore $store): void
    {
        $finder = $store->createFinder();

        self::assertInstanceOf(Finder::class, $finder);
        self::assertIsIterable($finder);
    }

    // public function testLoad()
    // {
    // }
}
