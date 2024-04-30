<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;

class OneClickInscriptionFinishResponseTest extends TestCase {

    protected $json;
    protected $inscriptionResponse;

    protected function setUp():void {
        $this->json = [
            'response_code' => '00',
            'tbk_user' => '123456',
            'authorization_code' => '7890',
            'card_type' => 'Visa',
            'card_number' => '**** **** **** 1234'
        ];
        $this->inscriptionResponse = new InscriptionFinishResponse($this->json);

    }
    /** @test */
    public function it_can_be_initialized_from_json()
    {
        $response = new InscriptionFinishResponse($this->json);

        $this->assertSame('00', $response->responseCode);
        $this->assertSame('123456', $response->tbkUser);
        $this->assertSame('7890', $response->authorizationCode);
        $this->assertSame('Visa', $response->cardType);
        $this->assertSame('**** **** **** 1234', $response->cardNumber);
    }

    /** @test */
    public function it_returns_true_when_response_code_is_approved()
    {
        $this->assertTrue($this->inscriptionResponse->isApproved());
    }

    /** @test */
    public function it_returns_false_when_response_code_is_not_approved()
    {
        $json = ['response_code' => '01'];
        $response = new InscriptionFinishResponse($json);
        $this->assertFalse($response->isApproved());
    }

    /** @test */
    public function it_can_get_response_code()
    {
        $this->assertSame(0, $this->inscriptionResponse->getResponseCode());
    }

    /** @test */
    public function it_can_get_tbk_user()
    {
        $this->assertSame('123456', $this->inscriptionResponse->getTbkUser());
    }

    /** @test */
    public function it_can_get_authorization_code()
    {
        $this->assertSame('7890', $this->inscriptionResponse->getAuthorizationCode());
    }

    /** @test */
    public function it_can_get_card_type()
    {
        $this->assertSame('Visa', $this->inscriptionResponse->getCardType());
    }

    /** @test */
    public function it_can_get_card_number()
    {
        $this->assertSame('**** **** **** 1234', $this->inscriptionResponse->getCardNumber());
    }
}
