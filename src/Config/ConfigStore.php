<?php

namespace Jascha030\ConfigurationLib\Config;

use Symfony\Component\Finder\Finder;

class ConfigStore
{
    private array $directories;

    private Finder $finder;

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

    public function load(): void
    {
        $iterator = $this->createFinder()->getIterator();

        foreach ($iterator as $file) {
            $config = include $file;

            if (! is_array($config)) {
                throw new \RuntimeException("Invalid config \"{$file->getRealPath()}\", Config files should return an array.");
            }

            $this->config[$file->getBasename()] = $config;
        }
    }

    public function addConfigDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            throw new \RuntimeException("Invalid configuration directory: \"{$directory}\"");
        }

        $this->directories[] = $directory;
    }

    private function createFinder(): Finder
    {
        $this->finder = (new Finder())->in($this->directories)->files()->name('*.php');

        return $this->finder;
    }
}
