<?php

namespace PayU\OpenPayU\AuthType;

interface AuthType
{

    /**
     * @return array
     */
    public function getHeaders();

}
