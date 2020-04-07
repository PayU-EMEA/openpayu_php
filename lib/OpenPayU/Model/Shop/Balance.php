<?php

class Balance
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
     * @return Balance
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
     * @return Balance
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
     * @return Balance
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
        return 'Balance [currencyCode=' . $this->currencyCode .
            ', total=' . $this->total .
            ', available=' . $this->available .
            ']';
    }
}
