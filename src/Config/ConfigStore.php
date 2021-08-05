<?php

namespace Jascha030\ConfigurationLib\Config;

use Symfony\Component\Finder\Finder;

class ConfigStore implements ConfigStoreInterface
{
    private array $directories;

    private array $config;

    public function __construct($directories)
    {
        $this->config = [];

        if (! is_array($directories) && ! is_string($directories)) {
            throw new \RuntimeException('Invalid parameter \$directory, please provide a path string or an array of path strings');
        }

        if (is_array($directories)) {
            $this->directories = $directories;
        }

        if (is_string($directories)) {
            $this->directories = [$directories];
        }

        if (empty($this->directories)) {
            throw new \RuntimeException('Could not load any of the provided configuration directories');
        }

        foreach ($this->directories as $directory) {
            if (! is_dir($directory)) {
                throw new \RuntimeException("Invalid configuration directory: \"{$directory}\"");
            }
        }
    }

    public function load(): ConfigStoreInterface
    {
        $iterator = $this->createFinder()->getIterator();

        foreach ($iterator as $file) {
            $config = include $file;

            if (! is_array($config)) {
                throw new \RuntimeException("Invalid config \"{$file->getRealPath()}\", Config files should return an array.");
            }

            $this->config[$file->getBasename('.php')] = $config;
        }

        return $this;
    }

    public function addConfigDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            throw new \RuntimeException("Invalid configuration directory: \"{$directory}\"");
        }

        $this->directories[] = $directory;
    }

    public function createFinder(): Finder
    {
        return (new Finder())->in($this->directories)->files()->name('*.php');
    }

    /**
     * @inheritdoc
     */
    public function get(string $option)
    {
        if (strpos($option, '.') !== false) {
            $optionArray = explode('.', $option);

            if (! $this->hasKey($optionArray[0], $optionArray[1])) {
                throw new \RuntimeException("Option: \"{$optionArray[1]}\" does not exist in config: \"{$optionArray[0]}\"");
            }

            return $this->config[$optionArray[0]][$optionArray[1]];
        }

        $file = $this->getFileByOptionKey($option);

        return $this->config[$file][$option];
    }

    public function getConfig(string $fileName): array
    {
        return $this->config[$fileName];
    }

    public function keyExists(string $key): bool
    {
        foreach ($this->config as $configurations) {
            if (array_key_exists($key, $configurations)) {
                return true;
            }
        }

        return false;
    }

    public function getFileByOptionKey(string $key): string
    {
        foreach ($this->config as $file => $configurations) {
            if (array_key_exists($key, $configurations)) {
                return $file;
            }
        }

        throw new \RuntimeException("Option: \"{$key}\" does not exist.");
    }

    public function hasKey(string $filename, string $key): bool
    {
        return isset($this->config[$filename][$key]);
    }

    public function configFileExists(string $fileName): bool
    {
        return isset($this->config[$fileName]);
    }
}
