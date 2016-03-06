<?php

interface AuthType
{

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return boolean
     */
    public function isAuthBasic();

}