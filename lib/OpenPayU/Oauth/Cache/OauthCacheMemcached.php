<?php

class OauthCacheMemcached implements OauthCacheInterface
{
    private $memcached;

    /**
     * @param string $host
     * @param int $port
     * @param int $weight
     * @throws OpenPayU_Exception_Configuration
     */
    public function __construct($host = 'localhost', $port = 11211, $weight = 0)
    {
        if (!class_exists('Memcached')) {
            throw new OpenPayU_Exception_Configuration('PHP Memcached extension not installed.');
        }

        $this->memcached = new Memcached('PayU');
        $this->memcached->addServer($host, $port, $weight);
        $stats = $this->memcached->getStats();
        if ($stats[$host . ':' . $port]['pid'] == -1) {
            throw new OpenPayU_Exception_Configuration('Problem with connection to memcached server [host=' . $host . '] [port=' . $port . '] [weight=' . $weight . ']');
        }
    }

    public function get($key)
    {
        $cache = $this->memcached->get($key);
        return $cache === false ? null : unserialize($cache);
    }

    public function set($key, $value)
    {
        return $this->memcached->set($key, serialize($value));
    }

    public function invalidate($key)
    {
        return $this->memcached->delete($key);
    }

}