<?php

namespace Config;

use Jascha030\ConfigurationLib\Config\ConfigStore;
use Jascha030\ConfigurationLib\Config\ConfigStoreInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class ConfigStoreTest extends TestCase
{
    private const NON_EXISTING_DIRECTORY = __DIR__ . '/doesNotExist';

    public function testConstruct(): ConfigStoreInterface
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
     * @param ConfigStoreInterface $store
     */
    public function testInvalidAddedDirectoryExceptionIsThrown(ConfigStoreInterface $store): void
    {
        $this->expectException(\RuntimeException::class);
        $store->addConfigDirectory(self::NON_EXISTING_DIRECTORY);
    }

    /**
     * @depends testConstruct
     *
     * @param ConfigStoreInterface $store
     */
    public function testAddConfigDirectory(ConfigStoreInterface $store): void
    {
        $store->addConfigDirectory(dirname(__DIR__) . '/Fixtures/ExtraConfigDirectory');
        self::assertTrue($store->load()->keyExists('github_url'));
    }

    /**
     * @depends testConstruct
     * @noinspection UnnecessaryAssertionInspection
     */
    public function testCreateFinder(ConfigStoreInterface $store): void
    {
        $finder = $store->createFinder();

        self::assertInstanceOf(Finder::class, $finder);
        self::assertIsIterable($finder);
    }

    /**
     * @depends testLoad
     *
     * @param ConfigStoreInterface $store
     */
    public function testKeyExists(ConfigStoreInterface $store): void
    {
        self::assertTrue($store->keyExists('firstName'));
        self::assertFalse($store->keyExists('nonExistentKey'));
    }

    /**
     * @depends testLoad
     *
     * @param ConfigStoreInterface $store
     */
    public function testGet(ConfigStoreInterface $store): void
    {
        self::assertEquals('john', $store->get('firstName'));
        self::assertEquals('john', $store->get('user.firstName'));
    }

    /**
     * @depends testLoad
     *
     * @param ConfigStoreInterface $store
     */
    public function testHasKey(ConfigStoreInterface $store): void
    {
        self::assertTrue($store->hasKey('user', 'firstName'));
        self::assertFalse($store->hasKey('user', 'nonExistentKey'));
    }

    /**
     * @depends testLoad
     *
     * @param ConfigStoreInterface $store
     */
    public function testGetConfig(ConfigStoreInterface $store): void
    {
        self::assertIsArray($store->getConfig('user'));
    }

    /**
     * @depends testLoad
     *
     * @param ConfigStoreInterface $store
     */
    public function testGetFileByOptionKey(ConfigStoreInterface $store): void
    {
        self::assertEquals('user', $store->getFileByOptionKey('firstName'));
    }

    /**
     * @noinspection UnnecessaryAssertionInspection
     * @depends      testConstruct
     *
     * @param ConfigStoreInterface $store
     *
     * @return ConfigStoreInterface
     */
    public function testLoad(ConfigStoreInterface $store): ConfigStoreInterface
    {
        self::assertInstanceOf(ConfigStore::class, $store->load());

        return $store;
    }

    /**
     * @depends testLoad
     *
     * @param ConfigStoreInterface $store
     */
    public function testConfigFileExists(ConfigStoreInterface $store): void
    {
        self::assertTrue($store->configFileExists('user'));
        self::assertFalse($store->configFileExists('file_that_does_not_exist'));
    }
}
