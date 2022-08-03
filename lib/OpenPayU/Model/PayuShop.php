<?php

class PayuShop
{
    /** @var string */
    private $shopId;

    /** @var string */
    private $name;

    /** @var string */
    private $currencyCode;

    /** @var PayuShopBalance */
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
     * @return PayuShop
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
     * @return PayuShop
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
     * @return PayuShop
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return PayuShopBalance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param PayuShopBalance $balance
     * @return PayuShop
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
        return 'PayuShop [shopId=' . $this->shopId .
            ', name=' . $this->name .
            ', currencyCode=' . $this->currencyCode .
            ', balance=' . $this->balance .
            ']';
    }
}
