<?php

namespace Omnipay\PaynlV3\Message\Response;

class FetchTransactionResponse extends AbstractPaynlResponse
{
    const STATUS_CANCEL = 'CANCEL';
    const STATUS_PENDING = 'PENDING';
    const STATUS_AUTHORIZED = 'AUTHORIZED';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_VERIFY = 'VERIFY';
    const STATUS_PAID = 'PAID';
    const STATUS_DENIED = 'DENIED';
    const STATUS_FAILED = 'FAILURE';

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return isset($this->data['status']['action']) && ($this->data['status']['action'] === self::STATUS_CANCEL
              || $this->data['status']['action'] === self::STATUS_DENIED
              || $this->data['status']['action'] === self::STATUS_FAILED);
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return isset($this->data['status']['action']) && $this->data['status']['action'] === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return $this->isPending();
    }

    /**
     * @return bool
     */
    public function isVerify()
    {
        return isset($this->data['status']['action']) && $this->data['status']['action'] === self::STATUS_VERIFY;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return isset($this->data['status']['action']) && $this->data['status']['action'] === self::STATUS_EXPIRED;
    }

    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->request->getTransactionReference() ?? $this->data['orderId'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getStatus()
    {
        return $this->data['status']['action'] ?? null;
    }

    /**
     * @return float|null
     */
    public function getAmount()
    {
        return isset($this->data['amount']['value']) ? $this->data['amount']['value'] / 100 : null;
    }

    /**
     * @return string|null The paid currency
     */
    public function getCurrency()
    {
        return $this->data['amount']['currency'] ?? null;
    }

    /**
     * @return boolean
     */
    public function isPaid()
    {
        return isset($this->data['status']['action']) && $this->data['status']['action'] === self::STATUS_PAID;
    }

    /**
     * @return boolean
     */
    public function isAuthorized()
    {
        return isset($this->data['status']['action']) && $this->data['status']['action'] === self::STATUS_AUTHORIZED;
    }
}
