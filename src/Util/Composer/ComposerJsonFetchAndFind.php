<?php

declare(strict_types = 1);

namespace Constup\JSONSerializer\Util\Composer;

use Exception;

/**
 * Class ComposerJsonFetchAndFind
 *
 * @package Constup\JSONSerializer\Util\Composer
 */
class ComposerJsonFetchAndFind
{
    const PSR_4 = 'psr-4';
    const AUTOLOAD_DEV = 'autoload-dev';

    /**
     * @param string $start_directory
     *
     * @throws Exception
     *
     * @return string
     */
    public static function findComposerJSON(string $start_directory): string
    {
        $_start_directory = rtrim($start_directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $composerJSON = $_start_directory . 'composer.json';
        if (!file_exists($composerJSON)) {
            if ($_start_directory == '/' || (preg_match('/^[a-zA-Z](\:\\\\)$/', $_start_directory))) {
                throw new Exception('composer.json not found.');
            }

            return self::findComposerJSON(dirname($_start_directory));
        }

        return $composerJSON;
    }

    /**
     * @param string $composerJSON_file_path
     *
     * @return object
     */
    public static function fetchComposerJSONObject(string $composerJSON_file_path): object
    {
        return json_decode(file_get_contents($composerJSON_file_path));
    }

    /**
     * @param string $start_directory
     *
     * @throws Exception
     *
     * @return object
     */
    public static function findAndFetch(string $start_directory): object
    {
        $location = self::findComposerJSON($start_directory);

        return self::fetchComposerJSONObject($location);
    }
}
