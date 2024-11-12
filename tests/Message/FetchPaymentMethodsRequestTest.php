<?php

namespace Omnipay\PaynlV3\Test\Message;


use Omnipay\Common\PaymentMethod;
use Omnipay\PaynlV3\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\PaynlV3\Message\Response\FetchPaymentMethodsResponse;
use Omnipay\Tests\TestCase;

class FetchPaymentMethodsRequestTest extends TestCase
{
    /**
     * @var FetchPaymentMethodsRequest
     */
    protected $request;

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchServiceConfigSuccess.txt');

        $response = $this->request->send();

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());

        $paymentMethods = $response->getPaymentMethods();

        $this->assertIsArray($paymentMethods);
        $this->assertNotEmpty($paymentMethods);
        $this->assertContainsOnlyInstancesOf(PaymentMethod::class, $paymentMethods);
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FetchServiceConfigFailed.txt');

        $response = $this->request->send();

        $this->assertInstanceOf(FetchPaymentMethodsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());

        $this->assertNotEmpty($response->getMessage());
        $this->assertNull($response->getPaymentMethods());
    }

    protected function setUp(): void
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'tokenCode' => 'AT-1234-5678',
            'apiSecret' => 'some-token',
            'serviceId' => 'SL-1234-5678',
            'tguDomain' => 'achterelkebetaling.nl',
        ]);
    }
}