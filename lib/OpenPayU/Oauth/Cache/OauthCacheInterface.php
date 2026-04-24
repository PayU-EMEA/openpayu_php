<?php

interface OauthCacheInterface
{

    public function get(string $key): ?OauthResultClientCredentials;

    public function set(string $key, OauthResultClientCredentials $value): bool;

    public function invalidate(string  $key): bool;
}
