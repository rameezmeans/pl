<?php

namespace Deyjandi\VivaWallet\Enums;

use Deyjandi\VivaWallet\Helpers\ClientAuth;
use Deyjandi\VivaWallet\Token;

enum Environment: string
{
    case Demo = 'demo';
    case Live = 'live';

    public function requestWebhookKey(string $merchantId, string $apiKey): array
    {
        return [
            'method' => 'GET',
            'uri' => match ($this) {
                self::Demo => 'https://demo.vivapayments.com/api/messages/config/token',
                self::Live => 'https://www.vivapayments.com/api/messages/config/token',
            },
            'options' => [
                'auth' => ClientAuth::basic($merchantId, $apiKey),
            ],
        ];
    }

    public function requestToken(string $clientId, string $clientSecret): array
    {
        return [
            'method' => 'POST',
            'uri' => match ($this) {
                self::Demo => 'https://demo-accounts.vivapayments.com/connect/token',
                self::Live => 'https://accounts.vivapayments.com/connect/token',
            },
            'options' => [
                'auth' => ClientAuth::basic($clientId, $clientSecret),
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'form_params' => ['grant_type' => 'client_credentials'],
            ],
        ];
    }

    public function createOrder(array $data): array
    {
        return [
            'method' => 'POST',
            'uri' => match ($this) {
                self::Demo => 'https://demo-api.vivapayments.com/checkout/v2/orders',
                self::Live => 'https://api.vivapayments.com/checkout/v2/orders',
            },
            'options' => [
                'headers' => [
                    'Authorization' => ClientAuth::token(Token::getInstance()),
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ],
        ];
    }

    public function checkout(string $order_code): string
    {
        return match ($this) {
            self::Demo => "https://demo.vivapayments.com/web/checkout?ref=$order_code",
            self::Live => "https://www.vivapayments.com/web/checkout?ref=$order_code",
        };
    }

    public function retrieveTransaction(string $transaction_id): array
    {
        return [
            'method' => 'GET',
            'uri' => match ($this) {
                self::Demo => "https://demo-api.vivapayments.com/checkout/v2/transactions/$transaction_id",
                self::Live => "https://api.vivapayments.com/checkout/v2/transactions/$transaction_id",
            },
            'options' => [
                'headers' => [
                    'Authorization' => ClientAuth::token(Token::getInstance()),
                ],
            ],
        ];
    }
}
