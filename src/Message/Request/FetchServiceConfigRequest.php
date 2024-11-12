<?php
namespace Omnipay\PaynlV3\Message\Request;

use Omnipay\PaynlV3\Message\Response\FetchServiceConfigResponse;

class FetchServiceConfigRequest extends AbstractPaynlRequest
{
    public function getData()
    {
        $this->validate('tokenCode', 'apiSecret', 'serviceId');

        return [
            'serviceId' => $this->getServiceId(),
        ];
    }

    /**
     * @param array $data
     * @return FetchServiceConfigResponse
     */
    public function sendData($data)
    {
        $responseData = $this->sendRequestRestApi('services/config?serviceId=' . $data['serviceId']);

        return $this->response = new FetchServiceConfigResponse($this, $responseData);
    }
}
