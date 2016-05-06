<?php


interface OauthCacheInterface
{

    /**
     * @param string $key
     * @return null | object
     */
    public function get($key);

    /**
     * @param string $key
     * @param object $value
     * @return bool
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @return bool
     */
    public function invalidate($key);

}