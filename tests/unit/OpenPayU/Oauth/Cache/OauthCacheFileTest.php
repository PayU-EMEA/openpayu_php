<?php

use PHPUnit\Framework\TestCase;

require_once realpath(__DIR__) . '/../../../../TestHelper.php';

class OauthCacheFileTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/oauthcache_test_' . uniqid('', true);
        mkdir($this->tempDir, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->tempDir . '/*') as $file) {
            unlink($file);
        }
        rmdir($this->tempDir);
    }

    private function buildCredentials(string $token = 'test-token', int $expiresIn = 3600): OauthResultClientCredentials
    {
        $creds = new OauthResultClientCredentials();
        $creds->setAccessToken($token);
        $creds->setTokenType('bearer');
        $creds->setExpiresIn($expiresIn);
        $creds->setGrantType('client_credentials');
        $creds->calculateExpireDate(new DateTime());
        return $creds;
    }

    /** @test */
    public function shouldThrowExceptionWhenDirectoryDoesNotExist(): void
    {
        $this->expectException(OpenPayU_Exception_Configuration::class);
        $this->expectExceptionMessageMatches('/not exist or not writable/');

        new OauthCacheFile('/nonexistent/path/that/does/not/exist');
    }

    /** @test */
    public function shouldThrowExceptionWhenDirectoryIsNotWritable(): void
    {
        $readonlyDir = $this->tempDir . '/readonly';
        mkdir($readonlyDir, 0444);

        try {
            $this->expectException(OpenPayU_Exception_Configuration::class);
            $this->expectExceptionMessageMatches('/not exist or not writable/');

            new OauthCacheFile($readonlyDir);
        } finally {
            chmod($readonlyDir, 0777);
            rmdir($readonlyDir);
        }
    }

    /** @test */
    public function shouldInstantiateWithValidDirectory(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $this->assertInstanceOf(OauthCacheFile::class, $cache);
    }

    /** @test */
    public function shouldInstantiateWithDefaultDirectory(): void
    {
        $cache = new OauthCacheFile();
        $this->assertInstanceOf(OauthCacheFile::class, $cache);
    }

    /** @test */
    public function shouldReturnNullWhenKeyNotFound(): void
    {
        $cache = new OauthCacheFile($this->tempDir);

        $result = $cache->get('nonexistent-key');

        $this->assertNull($result);
    }

    /** @test */
    public function shouldStoreAndRetrieveCredentials(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $creds = $this->buildCredentials('my-access-token');

        $cache->set('my-key', $creds);
        $result = $cache->get('my-key');

        $this->assertInstanceOf(OauthResultClientCredentials::class, $result);
        $this->assertSame('my-access-token', $result->getAccessToken());
        $this->assertSame('bearer', $result->getTokenType());
        $this->assertSame('client_credentials', $result->getGrantType());
    }

    /** @test */
    public function shouldReturnTrueOnSuccessfulSet(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $creds = $this->buildCredentials();

        $result = $cache->set('some-key', $creds);

        $this->assertTrue($result);
    }

    /** @test */
    public function shouldIsolateDifferentKeys(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $cache->set('key-a', $this->buildCredentials('token-a'));
        $cache->set('key-b', $this->buildCredentials('token-b'));

        $this->assertSame('token-a', $cache->get('key-a')->getAccessToken());
        $this->assertSame('token-b', $cache->get('key-b')->getAccessToken());
    }

    /** @test */
    public function shouldOverwriteExistingCacheEntry(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $cache->set('key', $this->buildCredentials('old-token'));
        $cache->set('key', $this->buildCredentials('new-token'));

        $result = $cache->get('key');
        $this->assertSame('new-token', $result->getAccessToken());
    }

    /** @test */
    public function shouldReturnNullForCorruptedCacheFile(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $corruptedFile = $this->tempDir . '/' . md5('corrupted-key');
        file_put_contents($corruptedFile, 'this is not valid serialized data }{{{');

        $result = $cache->get('corrupted-key');

        $this->assertNull($result);
    }

    /** @test */
    public function shouldReturnNullForNonOauthResultClientCredentialsInCacheFile(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $nonOauthResultClientCredentialsFile = $this->tempDir . '/' . md5('non-oauth-client-credentials');
        file_put_contents($nonOauthResultClientCredentialsFile, serialize(new stdClass()));

        $result = $cache->get('non-oauth-client-credentials');

        $this->assertNull($result);
    }

    /** @test */
    public function shouldInvalidateExistingCacheEntry(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $cache->set('key', $this->buildCredentials());

        $result = $cache->invalidate('key');

        $this->assertTrue($result);
        $this->assertNull($cache->get('key'));
    }

    /** @test */
    public function shouldReturnTrueWhenInvalidatingNonExistentKey(): void
    {
        $cache = new OauthCacheFile($this->tempDir);

        $result = $cache->invalidate('does-not-exist');

        $this->assertTrue($result);
    }

    /** @test */
    public function shouldRemoveCacheFileOnInvalidate(): void
    {
        $cache = new OauthCacheFile($this->tempDir);
        $key = 'key-to-remove';
        $cache->set($key, $this->buildCredentials());

        $cache->invalidate($key);

        $this->assertFileDoesNotExist($this->tempDir . '/' . md5($key));
    }
}
