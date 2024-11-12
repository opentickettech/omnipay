<?php

namespace Omnipay\PaynlV3\Message\Response;

abstract class AbstractPaynlResponseWithLinks extends AbstractPaynlResponse
{
    /**
     * @return null|string
     */
    public function getStatusUrl()
    {
        return $this->data['links']['status'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getAbortUrl()
    {
        return $this->data['links']['abort'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getApproveUrl()
    {
        return $this->data['links']['approve'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getDeclineUrl()
    {
        return $this->data['links']['decline'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getVoidUrl()
    {
        return $this->data['links']['void'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getCaptureUrl()
    {
        return $this->data['links']['capture'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getCaptureAmountUrl()
    {
        return $this->data['links']['captureAmount'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getCaptureProductsUrl()
    {
        return $this->data['links']['captureProducts'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getDebugUrl()
    {
        return $this->data['links']['debug'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getCheckoutUrl()
    {
        return $this->data['links']['checkout'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getRedirectUrl()
    {
        return $this->data['links']['redirect'] ?? null;
    }

    /**
     * @return boolean
     */
    public function isRedirect()
    {
        return !is_null($this->getRedirectUrl());
    }
}