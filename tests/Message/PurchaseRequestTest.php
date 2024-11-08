<?php

namespace Omnipay\PaynlV3\Test\Message;


use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\PaynlV3\Common\Item;
use Omnipay\PaynlV3\Message\Request\PurchaseRequest;
use Omnipay\PaynlV3\Message\Response\PurchaseResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    protected $request;

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseRequestSuccess.txt');

        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');

        $response = $this->request->send();
        $this->assertInstanceOf(PurchaseResponse::class, $response);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());

        $this->assertIsString($response->getTransactionReference());
        $this->assertIsString($response->getRedirectUrl());
        $this->assertIsString($response->getAcceptCode());

        $this->assertEquals($response->getTransactionReference(), '52200435050XXXXX');
        $this->assertEquals($response->getRedirectUrl(), 'https://cards.payments.nl/start/52200435050XXXXX/672dee9352238cc5254e0522004350505223af45/nl/?locale=nl_NL');
        $this->assertEquals($response->getAcceptCode(), 'a payment reference');

        $this->assertEquals('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
        $this->assertInstanceOf(RedirectResponseInterface::class, $response);
        $this->assertInstanceOf(RedirectResponse::class, $response->getRedirectResponse());
    }

    public function testCardAddress()
    {
        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');

        $card = $this->getValidCard();
        $objCard = new CreditCard($card);
        $this->request->setCard($objCard);

        $data = $this->request->getData();

        $this->assertNotEmpty($data['customer']);
        $this->assertNotEmpty($data['order']);
        $order = $data['order'];
        $this->assertNotEmpty($order['deliveryAddress']);
        $address = $order['deliveryAddress'];

        $strAddress = $objCard->getShippingAddress1() . ' ' . $objCard->getShippingAddress2();
        $arrAddressParts = $this->request->getAddressParts($strAddress);

        if (isset($arrAddressParts[0])) $this->assertEquals($arrAddressParts[0], $address['streetName']);
        if (isset($arrAddressParts[1])) $this->assertEquals($arrAddressParts[1], $address['streetNumber']);
        if (isset($arrAddressParts[2])) $this->assertEquals($arrAddressParts[2], $address['streetNumberExtension']);

        $this->assertEquals($objCard->getShippingPostcode(), $address['zipCode']);
        $this->assertEquals($objCard->getShippingCity(), $address['city']);
        $this->assertEquals($objCard->getShippingCountry(), $address['country']);
        $this->assertEquals($objCard->getShippingState(), $address['region']);

    }

    public function testStatsData()
    {
        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');

        $statsData = [
            'promotorId' => uniqid(),
            'info' => uniqid(),
            'tool' => uniqid(),
            'extra1' => uniqid(),
            'extra2' => uniqid(),
            'extra3' => uniqid()
        ];

        $this->request->setStatsData($statsData);

        $data = $this->request->getData();

        $this->assertArrayHasKey('stats', $data);
        $this->assertEquals($statsData['promotorId'], $data['stats']['promotorId']);
        $this->assertEquals($statsData['info'], $data['stats']['info']);
        $this->assertEquals($statsData['tool'], $data['stats']['tool']);
        $this->assertEquals($statsData['extra1'], $data['stats']['extra1']);
        $this->assertEquals($statsData['extra2'], $data['stats']['extra2']);
        $this->assertEquals($statsData['extra3'], $data['stats']['extra3']);
    }

    public function testDates()
    {
        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');

        $invoiceDate = new \DateTime('now');
        $deliveryDate = new \DateTime('tomorrow');
        $expireDate = new \DateTime('now + 4 hours');

        $invoiceDate = $invoiceDate->format('d-m-Y');
        $deliveryDate = $deliveryDate->format('d-m-Y');
        $expireDate = $expireDate->format('d-m-Y H:i:s');

        $this->request->setInvoiceDate($invoiceDate);
        $this->request->setDeliveryDate($deliveryDate);
        $this->request->setExpireDate($expireDate);

        $data = $this->request->getData();

        $this->assertArrayHasKey('order', $data);
        $this->assertArrayHasKey('invoiceDate', $data['order']);
        $this->assertArrayHasKey('deliveryDate', $data['order']);
        $this->assertArrayHasKey('expire', $data);
        $this->assertEquals($invoiceDate, $data['order']['invoiceDate']);
        $this->assertEquals($deliveryDate, $data['order']['deliveryDate']);
        $this->assertEquals($expireDate, $data['expire']);
    }

    public function testCustomerData()
    {
        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');

        $customerReference = uniqid();
        $customerTrust = rand(-10, 10);

        $this->request->setCustomerReference($customerReference);
        $this->request->setCustomerTrust($customerTrust);

        $data = $this->request->getData();

        $this->assertArrayHasKey('customer', $data);
        $this->assertArrayHasKey('reference', $data['customer']);
        $this->assertArrayHasKey('trust', $data['customer']);
        $this->assertEquals($customerReference, $data['customer']['reference']);
        $this->assertEquals($customerTrust, $data['customer']['trust']);
    }


    public function testCardInvoiceAddress()
    {
        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');

        $card = $this->getValidCard();
        $objCard = new CreditCard($card);
        $this->request->setCard($objCard);

        $data = $this->request->getData();

        $this->assertNotEmpty($data['order']);
        $enduser = $data['order'];
        $this->assertNotEmpty($enduser['invoiceAddress']);
        $address = $enduser['invoiceAddress'];

        $strAddress = $objCard->getBillingAddress1() . ' ' . $objCard->getBillingAddress2();
        $arrAddressParts = $this->request->getAddressParts($strAddress);

        $this->assertEquals($objCard->getBillingFirstName(), $address['firstName']);
        $this->assertEquals($objCard->getBillingLastName(), $address['lastName']);

        if (isset($arrAddressParts[0])) $this->assertEquals($arrAddressParts[0], $address['streetName']);
        if (isset($arrAddressParts[1])) $this->assertEquals($arrAddressParts[1], $address['streetNumber']);
        if (isset($arrAddressParts[2])) $this->assertEquals($arrAddressParts[2], $address['streetNumberExtension']);

        $this->assertEquals($objCard->getBillingPostcode(), $address['zipCode']);
        $this->assertEquals($objCard->getBillingCity(), $address['city']);
        $this->assertEquals($objCard->getBillingCountry(), $address['country']);
        $this->assertEquals($objCard->getBillingState(), $address['region']);

    }

    public function testPaynlItem()
    {
        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');
        $this->request->setNotifyUrl('https://www.pay.nl/exchange');


        $name = uniqid();
        $price = rand(1, 1000) / 100;
        $quantity = rand(1, 10);
        $productId = uniqid();
        $vatPercentage = rand(0, 21);

        $objItem = new Item([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'productId' => $productId,
            'productType' => Item::PRODUCT_TYPE_ARTICLE,
            'vatPercentage' => $vatPercentage
        ]);

        $this->request->setItems([$objItem]);

        $data = $this->request->getData();

        $this->assertNotEmpty($data['order']['products'][0]);
        $item = $data['order']['products'][0];

        $this->assertEquals($objItem->getProductId(), $item['id']);
        $this->assertEquals($objItem->getName(), $item['description']);
        $this->assertEquals(round($objItem->getPrice() * 100), $item['price']['value']);
        $this->assertEquals($objItem->getQuantity(), $item['quantity']);
        $this->assertEquals($objItem->getProductType(), $item['productType']);
        $this->assertEquals($objItem->getVatPercentage(), $item['vatPercentage']);
    }

    public function testStockItem()
    {
        $this->request->setAmount(1);
        $this->request->setClientIp('10.0.0.5');
        $this->request->setReturnUrl('https://www.pay.nl');
        $this->request->setNotifyUrl('https://www.pay.nl/exchange');

        $name = uniqid();
        $price = rand(1, 1000) / 100;
        $quantity = rand(1, 10);

        $objItem = new \Omnipay\Common\Item([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
        ]);

        $this->request->setItems([$objItem]);

        $data = $this->request->getData();

        $this->assertNotEmpty($data['order']['products'][0]);
        $item = $data['order']['products'][0];

        $this->assertEquals($objItem->getName(), $item['description']);
        $this->assertEquals(round($objItem->getPrice() * 100), $item['price']['value']);
        $this->assertEquals($objItem->getQuantity(), $item['quantity']);

    }

    protected function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize([
            'tokenCode' => 'AT-1234-5678',
            'apiSecret' => 'some-token',
            'serviceId' => 'SL-1234-5678',
            'tguDomain' => 'achterelkebetaling.nl',
        ]);
    }
}