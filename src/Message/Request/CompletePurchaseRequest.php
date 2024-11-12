<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\CompletePurchaseResponse;

class CompletePurchaseRequest extends AbstractPaynlRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'transactionReference');

        return [
            'transactionId' => $this->getParameter('transactionReference')
        ];
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface|CompletePurchaseResponse
     */
    public function sendData($data)
    {
        $url = '/' . $data['transactionId'] . '/status';
        $responseData = $this->sendRequestMultiCore($url);
        return $this->response = new CompletePurchaseResponse($this, $responseData);
    }
}
