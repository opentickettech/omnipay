<?php

namespace Omnipay\PaynlV3\Message\Response;

class RefundResponse extends AbstractPaynlResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return !is_null($this->getTransactionReference());
    }

    /**
     * @return string Description of the refund
     */
    public function getDescription()
    {
        return $this->data['description'];
    }

    /**
     * @return integer
     */
    public function getAmountInteger()
    {
        return $this->data['amountRefunded']['value'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->data['transactionId'] ?? null;
    }
}