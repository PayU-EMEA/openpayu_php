<?php

class PayuShopBalance
{
    /** @var string */
    private $currencyCode;

    /** @var int */
    private $total;

    /** @var int */
    private $available;

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return PayuShopBalance
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return PayuShopBalance
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return int
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param int $available
     * @return PayuShopBalance
     */
    public function setAvailable($available)
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'PayuShopBalance [currencyCode=' . $this->currencyCode .
            ', total=' . $this->total .
            ', available=' . $this->available .
            ']';
    }
}
