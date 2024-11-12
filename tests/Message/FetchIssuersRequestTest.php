<?php

namespace Omnipay\PaynlV3\Test\Message;


use Omnipay\Common\Issuer;
use Omnipay\PaynlV3\Message\Request\FetchIssuersRequest;
use Omnipay\PaynlV3\Message\Response\FetchIssuersResponse;
use Omnipay\Tests\TestCase;

class FetchIssuersRequestTest extends TestCase
{
    /**
     * @var FetchIssuersRequest
     */
    protected $request;

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchServiceConfigSuccess.txt');

        $response = $this->request->send();

        $this->assertInstanceOf(FetchIssuersResponse::class, $response);
        $this->assertTrue($response->isSuccessful());

        $issuers = $response->getIssuers();
        $this->assertNotEmpty($issuers);

        $this->assertContainsOnlyInstancesOf(Issuer::class, $issuers);
    }

    protected function setUp(): void
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'tokenCode' => 'AT-1234-5678',
            'apiSecret' => 'some-token',
            'serviceId' => 'SL-1234-5678',
            'tguDomain' => 'achterelkebetaling.nl',
        ]);
    }
}