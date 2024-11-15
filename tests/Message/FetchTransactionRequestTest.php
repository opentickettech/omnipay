<?php

namespace Omnipay\PaynlV3\Test\Message;


use Omnipay\PaynlV3\Message\Request\FetchTransactionRequest;
use Omnipay\PaynlV3\Message\Response\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var FetchTransactionRequest
     */
    protected $request;

    public function testSendSuccessPaid()
    {
        $this->setMockHttpResponse('FetchTransactionRequestPaid.txt');

        $transactionReference = uniqid();
        $this->request->setTransactionReference($transactionReference);

        $this->assertEquals($transactionReference, $this->request->getTransactionReference());

        $response = $this->request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);

        $this->assertEquals($transactionReference, $response->getTransactionReference());

        $this->assertTrue($response->isSuccessful());

        $this->assertTrue($response->isPaid());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isOpen());
        $this->assertFalse($response->isAuthorized());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isExpired());
        $this->assertFalse($response->isVerify());

        $this->assertEquals('PAID', $response->getStatus());

        $this->assertEquals('0.01', $response->getAmount(), 'Amount should be 0.01 EUR');
        $this->assertEquals('EUR', $response->getCurrency(), 'Amount should be 0.01 EUR');
    }

    public function testSendSuccessDenied()
    {
        $this->setMockHttpResponse('FetchTransactionRequestDenied.txt');

        $transactionReference = uniqid();
        $this->request->setTransactionReference($transactionReference);

        $this->assertEquals($transactionReference, $this->request->getTransactionReference());

        $response = $this->request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);

        $this->assertEquals($transactionReference, $response->getTransactionReference());

        $this->assertTrue($response->isSuccessful());

        $this->assertFalse($response->isPaid());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isOpen());
        $this->assertFalse($response->isAuthorized());
        $this->assertTrue($response->isCancelled());
        $this->assertFalse($response->isExpired());
        $this->assertFalse($response->isVerify());

        $this->assertEquals('DENIED', $response->getStatus());

        $this->assertEquals('0.01', $response->getAmount(), 'Amount should be 0.01 EUR');
        $this->assertEquals('EUR', $response->getCurrency(), 'Amount should be 0.01 EUR');
    }

    public function testSendSuccessFailed()
    {
        $this->setMockHttpResponse('FetchTransactionRequestFailed.txt');

        $transactionReference = uniqid();
        $this->request->setTransactionReference($transactionReference);

        $this->assertEquals($transactionReference, $this->request->getTransactionReference());

        $response = $this->request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);

        $this->assertEquals($transactionReference, $response->getTransactionReference());

        $this->assertTrue($response->isSuccessful());

        $this->assertFalse($response->isPaid());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isOpen());
        $this->assertFalse($response->isAuthorized());
        $this->assertTrue($response->isCancelled());
        $this->assertFalse($response->isExpired());
        $this->assertFalse($response->isVerify());

        $this->assertEquals('FAILURE', $response->getStatus());

        $this->assertEquals('0.01', $response->getAmount(), 'Amount should be 0.01 EUR');
        $this->assertEquals('EUR', $response->getCurrency(), 'Amount should be 0.01 EUR');
    }

    public function testSendSuccessCancelled()
    {
        $this->setMockHttpResponse('FetchTransactionRequestCancelled.txt');

        $transactionReference = uniqid();
        $this->request->setTransactionReference($transactionReference);

        $this->assertEquals($transactionReference, $this->request->getTransactionReference());

        $response = $this->request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);

        $this->assertEquals($transactionReference, $response->getTransactionReference());

        $this->assertTrue($response->isSuccessful());

        $this->assertFalse($response->isPaid());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isOpen());
        $this->assertFalse($response->isAuthorized());
        $this->assertTrue($response->isCancelled());
        $this->assertFalse($response->isExpired());
        $this->assertFalse($response->isVerify());

        $this->assertEquals('CANCEL', $response->getStatus());

        $this->assertEquals('0.01', $response->getAmount(), 'Amount should be 0.01 EUR');
        $this->assertEquals('EUR', $response->getCurrency(), 'Amount should be 0.01 EUR');
    }

    public function testSendSuccessPending()
    {
        $this->setMockHttpResponse('FetchTransactionRequestPending.txt');

        $transactionReference = uniqid();
        $this->request->setTransactionReference($transactionReference);

        $this->assertEquals($transactionReference, $this->request->getTransactionReference());

        $response = $this->request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertEquals($transactionReference, $response->getTransactionReference());

        $this->assertTrue($response->isSuccessful());

        $this->assertFalse($response->isPaid());
        $this->assertTrue($response->isPending());
        $this->assertTrue($response->isOpen());
        $this->assertFalse($response->isAuthorized());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isExpired());
        $this->assertFalse($response->isVerify());

        $this->assertEquals('PENDING', $response->getStatus());

        $this->assertEquals('0.01', $response->getAmount(), 'Amount should be 0.01 EUR');
        $this->assertEquals('EUR', $response->getCurrency(), 'Amount should be 0.01 EUR');
    }

    protected function setUp(): void
    {
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'tokenCode' => 'AT-1234-5678',
            'apiSecret' => 'some-token',
            'serviceId' => 'SL-1234-5678',
            'tguDomain' => 'achterelkebetaling.nl',
        ]);
    }
}