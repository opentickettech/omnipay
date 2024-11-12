<?php

namespace Omnipay\PaynlV3\Test\Message;


use Omnipay\Common\Issuer;
use Omnipay\PaynlV3\Message\Request\FetchIssuersRequest;
use Omnipay\PaynlV3\Message\Response\FetchIssuersResponse;
use Omnipay\PaynlV3\Message\Request\FetchServiceConfigRequest;
use Omnipay\PaynlV3\Message\Response\FetchServiceConfigResponse;
use Omnipay\Tests\TestCase;

class FetchServiceConfigRequestTest extends TestCase
{
    /**
     * @var FetchServiceConfigRequest
     */
    protected $request;

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchServiceConfigSuccess.txt');

        $response = $this->request->send();

        $this->assertInstanceOf(FetchServiceConfigResponse::class, $response);
    }

    protected function setUp(): void
    {
        $this->request = new FetchServiceConfigRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'tokenCode' => 'AT-1234-5678',
            'apiSecret' => 'some-token',
            'serviceId' => 'SL-1234-5678',
            'tguDomain' => 'achterelkebetaling.nl',
        ]);
    }
}