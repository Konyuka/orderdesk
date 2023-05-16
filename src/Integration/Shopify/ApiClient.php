<?php

namespace OrderDesk\App\Integration\Shopify;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    protected $client;

    // Moved Guzzle HTTP client instantiation to constructor    
    public function __construct()
    {
        $this->client = new Client();
    }

    public function getNumberOfOrders(): int
    {
        try {
            $response = $this->client->request('GET', 'https://mysuperawesomestorewithstuffandthings.shopify.com/orders');
        } catch (GuzzleException $e) {
            return 0;
        }

        $body = json_decode($response->getBody(), true);

        if (isset($body['status']) && $body['status'] === 'success' && isset($body['items'])) {
            return count($body['items']);
        }

        return 0;

    }

    public function getShipments(int $orderId): array
    {
        try {
            $response = $this->client->request('GET', "https://mysuperawesomestorewithstuffandthings.shopify.com/orders/shipments/$orderId");
        } catch (GuzzleException $e) {
            return [];
        }

        $body = json_decode($response->getBody(), true);

        $shipmentInfo = [];

        foreach ($body as $shipment) {
            $shipmentInfo[] = $shipment['tracking_number'];
        }

        return $shipmentInfo;

    }

}