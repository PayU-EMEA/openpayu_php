<?php

namespace PayU\OpenPayU\Oauth\Cache;

use PayU\OpenPayU\Exception\OpenPayUExceptionConfiguration;

class OauthCacheFile implements OauthCacheInterface
{
    private $directory;

    /**
     * @param string $directory
     * @throws OpenPayUExceptionConfiguration
     */
    public function __construct($directory = null)
    {
        if ($directory === null) {
            $directory = dirname(__FILE__).'/../../../Cache';
        }

        if (!is_dir($directory) || !is_writable($directory)) {
            throw new OpenPayUExceptionConfiguration('Cache directory [' . $directory . '] not exist or not writable.');
        }

        $this->directory = $directory . (substr($directory, -1) != '/' ? '/' : '');
    }

    public function get($key)
    {
        $cache = @file_get_contents($this->directory . md5($key));
        return $cache === false ? null : unserialize($cache);
    }

    public function set($key, $value)
    {
        return @file_put_contents($this->directory . md5($key), serialize($value));
    }

    public function invalidate($key)
    {
        return @unlink($this->directory . md5($key));
    }

}
