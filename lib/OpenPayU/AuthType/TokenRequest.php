<?php

namespace PayU\OpenPayU\AuthType;

class TokenRequest implements AuthType
{

    public function getHeaders()
    {
        return array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: */*'
        );
    }

}
