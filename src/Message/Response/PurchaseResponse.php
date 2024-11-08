<?php

namespace Omnipay\PaynlV3\Message\Response;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractPaynlResponseWithLinks implements RedirectResponseInterface
{
    /**
     * When you do a `purchase` the request is never successful because
     * you need to redirect off-site to complete the purchase.
     *
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * @returns bool The order is created
     */
    public function isSuccessfulCreated()
    {
        return isset($this->data['id']) ? true : false;

    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getTransactionReference()
    {
        return $this->data['orderId'] ?? null;
    }

    /**
     * @return string|null The payment accept code used to identify bank transfers
     */
    public function getAcceptCode()
    {
        return $this->data['reference'] ?? null;
    }
}
