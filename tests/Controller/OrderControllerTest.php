<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    private $client;
    private $baseUrl;

    public function setUp(): void
    {
        $this->baseUrl = getenv('TEST_BASE_URL');
        $this->client = static::createClient();
    }

    public function testAPIRequest(): void
    {
        $this->client->request('GET', $this->baseUrl . '/api/orders');
        $message = $this->client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
    }

    public function testCreateErrorVoucher(): void
    {
        $data = array(
            'amount' => '100',
        );
        $this->client->request('POST',
            $this->baseUrl . '/api/order',
            [], [], [], json_encode($data));
        $data = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Please provide the valid data', $data);
    }

    public function testCreateVoucher(): void
    {
        $data = array(
            'amount' => '1231'.rand(),
            'customer_id' => '1',
        );
        $this->client->request('POST',
            $this->baseUrl . '/api/order',
            [], [], [], json_encode($data));
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
    }

    public function testCreateVoucherWithOrder(): void
    {
        $payload = array(
            'amount' => '1231'.rand(),
            'customer_id' => '1',
            'voucher' => '123423'
        );
        $this->client->request('POST',
            $this->baseUrl . '/api/order',
            [], [], [], json_encode($payload));
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertStringContainsString('Invalid Voucher Details', $data['message']);
    }
}