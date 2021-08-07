<?php

namespace Jascha030\ConfigurationLib\Config;

use Symfony\Component\Finder\Finder;

interface ConfigStoreInterface
{
    public function load(): ConfigStoreInterface;

    public function addConfigDirectory(string $directory): void;

    /**
     * Retrieve an option by key, dot notation can be used to specify which file needs to be searched.
     * E.g. user.firstName would retrieve the firstName option from config file; user.php
     *
     * @param string $option
     *
     * @return mixed
     */
    public function get(string $option);

    public function getConfig(string $fileName): array;

    public function keyExists(string $key): bool;

    public function getFileByOptionKey(string $key): string;

    public function hasKey(string $filename, string $key): bool;

    public function configFileExists(string $fileName): bool;
}
