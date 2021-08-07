<?php

namespace Jascha030\ConfigurationLib\Config;

interface ConfigStoreInterface
{
    /**
     * Loads all files and their contents.
     * @return \Jascha030\ConfigurationLib\Config\ConfigStoreInterface
     */
    public function load(): ConfigStoreInterface;

    /**
     * Add a config directory, all .php files in this directory will be considered config files.
     * Call load(); to load newly added directories.
     *
     * @param string $directory
     */
    public function addConfigDirectory(string $directory): void;

    /**
     * Retrieve an option by key, dot notation can be used to specify which file needs to be searched.
     * E.g. user.firstName would retrieve the firstName option from config file; user.php.
     *
     * @param string $option
     *
     * @return mixed
     */
    public function get(string $option);

    /**
     * Get config array by it's file basename.
     *
     * @param string $fileName
     *
     * @return array
     */
    public function getConfig(string $fileName): array;

    /**
     * Check if an option exists by it's key.
     * This method searches all config files.
     *
     * @param string $key
     *
     * @return bool
     */
    public function keyExists(string $key): bool;

    /**
     * Find the config file an option belongs to.
     *
     * @param string $key
     *
     * @return string
     */
    public function getFileByOptionKey(string $key): string;

    /**
     * Validate that a file includes an option.
     *
     * @param string $filename
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $filename, string $key): bool;

    /**
     * Check if config file exists by it's basename.
     *
     * @param string $fileName
     *
     * @return bool
     */
    public function configFileExists(string $fileName): bool;
}
