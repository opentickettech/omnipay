<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\FetchServiceConfigResponse;

class FetchServiceConfigRequest extends AbstractPaynlRequest
{
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret');
    }

    /**
     * @param array $data
     * @return FetchServiceConfigResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequestRestApi('services/config');
        return $this->response = new FetchServiceConfigResponse($this, $responseData);
    }

    /**
     * @return string
     */
    public function getStatusUrl()
    {
        return $this->getParameter('statusUrl');
    }

    /**
     * @param $value string
     * @return $this
     */
    public function setStatusUrl($value)
    {
        return $this->setParameter('statusUrl', $value);
    }
}