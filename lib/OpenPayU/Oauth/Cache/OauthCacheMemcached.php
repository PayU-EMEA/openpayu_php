<?php

class OauthCacheMemcached implements OauthCacheInterface
{
    private \Memcached $memcached;

    /**
     * @throws OpenPayU_Exception_Configuration
     */
    public function __construct(string $host = 'localhost', int $port = 11211, int $weight = 0)
    {
        if (!class_exists('Memcached')) {
            throw new OpenPayU_Exception_Configuration('PHP Memcached extension not installed.');
        }

        $this->memcached = new Memcached();
        $this->memcached->addServer($host, $port, $weight);
        $stats = $this->memcached->getStats();
        if ($stats[$host . ':' . $port]['pid'] === -1) {
            throw new OpenPayU_Exception_Configuration('Problem with connection to memcached server [host=' . $host . '] [port=' . $port . '] [weight=' . $weight . ']');
        }
    }

    public function get(string $key): ?OauthResultClientCredentials
    {
        $cache = $this->memcached->get($key);
        try {
            return $cache === false ? null : unserialize(
                $cache,
                ['allowed_classes' => [OauthResultClientCredentials::class]]
            );
        } catch (\Error $e) {
            return null;
        }
    }

    public function set(string $key, OauthResultClientCredentials $value): bool
    {
        return $this->memcached->set($key, serialize($value));
    }

    public function invalidate(string $key): bool
    {
        return $this->memcached->delete($key);
    }
}
