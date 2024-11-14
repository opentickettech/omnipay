<?php

namespace Omnipay\PaynlV3\Message\Response;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\PaynlV3\Message\Request\AbstractPaynlRequest;

abstract class AbstractPaynlResponse extends AbstractResponse
{
    /**
     * @var AbstractPaynlRequest
     */
    protected $request;

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['id']);
    }

    /**
     * @return null|string The error message
     */
    public function getMessage()
    {
        return rtrim(@$this->data['title'] . ' ' . @$this->data['detail']);
    }

}
