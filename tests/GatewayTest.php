<?php

namespace Omnipay\PaynlV3\Test;

use Omnipay\PaynlV3\Gateway;
use Omnipay\PaynlV3\Message\Request\CaptureRequest;
use Omnipay\PaynlV3\Message\Request\CompletePurchaseRequest;
use Omnipay\PaynlV3\Message\Request\FetchIssuersRequest;
use Omnipay\PaynlV3\Message\Request\FetchPaymentMethodsRequest;
use Omnipay\PaynlV3\Message\Request\FetchTransactionRequest;
use Omnipay\PaynlV3\Message\Request\PurchaseRequest;
use Omnipay\PaynlV3\Message\Request\RefundRequest;
use Omnipay\PaynlV3\Message\Request\VoidRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway();
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction();
        $this->assertInstanceOf(FetchTransactionRequest::class, $request);
    }

    public function testFetchPaymentMethods()
    {
        $request = $this->gateway->fetchPaymentMethods();
        $this->assertInstanceOf(FetchPaymentMethodsRequest::class, $request);
    }

    public function testFetchIssuers()
    {
        $request = $this->gateway->fetchIssuers();
        $this->assertInstanceOf(FetchIssuersRequest::class, $request);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase();
        $this->assertInstanceOf(PurchaseRequest::class, $request);
    }

    public function testVoid()
    {
        $request = $this->gateway->void();
        $this->assertInstanceOf(VoidRequest::class, $request);
    }

    public function testCapture()
    {
        $request = $this->gateway->capture();
        $this->assertInstanceOf(CaptureRequest::class, $request);
    }

    public function testRefund()
    {
        $request = $this->gateway->refund();
        $this->assertInstanceOf(RefundRequest::class, $request);
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf(CompletePurchaseRequest::class, $request);
    }
}
