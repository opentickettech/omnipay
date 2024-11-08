<?php

namespace Omnipay\PaynlV3\Test\Message;


use Omnipay\PaynlV3\Message\Request\RefundRequest;
use Omnipay\PaynlV3\Message\Response\RefundResponse;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     * @var RefundRequest
     */
    protected $request;

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundRequestSuccess.txt');

        $transactionId = uniqid();

        $this->request->setTransactionReference($transactionId);

        $response = $this->request->send();

        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertEquals('EX-9167-XXXX-XXXX', $response->getTransactionReference());
        $this->assertTrue($response->isSuccessful());
        $this->assertIsString($response->getDescription());
        $this->assertIsInt($response->getAmountInteger());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('RefundRequestFailed.txt');

        $transactionId = uniqid();

        $this->request->setTransactionReference($transactionId);

        $response = $this->request->send();

        $this->assertInstanceOf(RefundResponse::class, $response);
        $this->assertNull($response->getTransactionReference());

        $this->assertFalse($response->isSuccessful());
        $this->assertNotEmpty($response->getMessage());
    }

    public function testAmount()
    {
        $transactionId = uniqid();
        $amount = rand(1, 100);

        $this->request->setTransactionReference($transactionId);
        $this->request->setAmountInteger($amount);

        $data = $this->request->getData();

        $this->assertEquals($transactionId, $data['transactionId']);
        $this->assertEquals($amount, $data['amount']);
    }

    public function testDescription()
    {
        $transactionId = uniqid();
        $description = uniqid();

        $this->request->setTransactionReference($transactionId);
        $this->request->setDescription($description);

        $data = $this->request->getData();

        $this->assertEquals($transactionId, $data['transactionId']);
        $this->assertEquals($description, $data['description']);
    }

    protected function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'tokenCode' => 'AT-1234-5678',
            'apiSecret' => 'some-token',
            'serviceId' => 'SL-1234-5678',
            'tguDomain' => 'achterelkebetaling.nl',
        ]);
    }
}