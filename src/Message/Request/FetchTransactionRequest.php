<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\FetchTransactionResponse;

class FetchTransactionRequest extends AbstractPaynlRequest
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
     * @return \Omnipay\Common\Message\ResponseInterface|FetchTransactionResponse
     */
    public function sendData($data)
    {
        $statusUrl = '/transactions/' . $data['transactionId'];
        $responseData = $this->sendRequestRestApi($statusUrl);
        return $this->response = new FetchTransactionResponse($this, $responseData);
    }
}
