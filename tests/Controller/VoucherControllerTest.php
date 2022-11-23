<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VoucherControllerTest extends WebTestCase
{
    private $client;
    private $baseUrl;

    public function setUp(): void
    {
        $this->baseUrl = getenv('TEST_BASE_URL');
        $this->client = static::createClient();
    }

    public function testAPIRequestValidation(): void
    {
        $this->client->request('GET', $this->baseUrl . '/api/vouchers');
        $message = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Please provide valid request', $message);
    }

    public function testAPIWithNoRecords(): void
    {
        $this->client->request('GET', $this->baseUrl . '/api/vouchers?type=active');
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
    }

    public function testCreateErrorVoucher(): void
    {
        $data = array(
            'description' => 'test11@test.com',
            'code' => 'Test name',
        );
        $this->client->request('POST',
            $this->baseUrl . '/api/voucher',
            [], [], [], json_encode($data));
        $data = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Please provide the valid data', $data);
    }

    public function testCreateVoucher(): void
    {
        $data = array(
            'description' => 'Sample',
            'code' => '123'.rand(),
            'type' => '1',
            'discount_amount' => '10',
            'expires_at' => '2023-08-10 21:35:05'
        );
        $this->client->request('POST',
            $this->baseUrl . '/api/voucher',
            [], [], [], json_encode($data));
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
    }

    public function testDeleteVoucher(): void
    {
        $this->client->request('DELETE',
            $this->baseUrl . '/api/voucher/12222',
            [], [], []);
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
    }
}