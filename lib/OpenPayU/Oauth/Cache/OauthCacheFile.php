<?php

class OauthCacheFile implements OauthCacheInterface
{
    private ?string $directory;

    /**
     * @throws OpenPayU_Exception_Configuration
     */
    public function __construct(?string $directory = null)
    {
        if ($directory === null) {
            $directory = __DIR__ . '/../../../Cache';
        }

        if ( ! is_dir($directory) || ! is_writable($directory)) {
            throw new OpenPayU_Exception_Configuration(
                'Cache directory [' . $directory . '] not exist or not writable.'
            );
        }

        $this->directory = $directory . (substr($directory, -1) !== '/' ? '/' : '');
    }

    public function get(string $key): ?OauthResultClientCredentials
    {
        $cacheFile = $this->getFilePath($key);

        try {
            return file_exists($cacheFile) ? unserialize(
                file_get_contents($cacheFile),
                ['allowed_classes' => [OauthResultClientCredentials::class, \DateTime::class]]
            ) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function set(string $key, OauthResultClientCredentials $value): bool
    {
        return file_put_contents($this->directory . md5($key), serialize($value)) !== false;
    }

    public function invalidate(string $key): bool
    {
        $cacheFile = $this->getFilePath($key);

        return !file_exists($cacheFile) || unlink($cacheFile);
    }

    private function getFilePath(string $key): string
    {
        return $this->directory . md5($key);
    }
}
