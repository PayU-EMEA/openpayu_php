<?php

class Shop
{
    /** @var string */
    private $shopId;

    /** @var string */
    private $name;

    /** @var string */
    private $currencyCode;

    /** @var Balance */
    private $balance;

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @param string $shopId
     * @return Shop
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Shop
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return Shop
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return Balance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param Balance $balance
     * @return Shop
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Shop [currencyCode=' . $this->shopId .
            ', name=' . $this->name .
            ', currencyCode=' . $this->currencyCode .
            ', balance=' . $this->balance .
            ']';
    }
}
