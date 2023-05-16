<?php

namespace OrderDesk\App\Integration\Shopify;

use GuzzleHttp\Client;

class ApiClient
{
    public function get_number_of_orders()
    {
        $httpClient = new Client();

        try {
            $a = $httpClient->request('GET', 'https://mysuperawesomestorewithstuffandthings.shopify.com/orders');
        } catch (\Exception $e) {}

        if ($a->getStatusCode() == 200) {
            if (strpos($a->getBody(), '{') === 0) {
                $data = json_decode($a->getBody());
                if (isset($data['status']) && $data['status'] == 'success') {
                    if ($data['items']) {
                        return count($data['items']);
                    }
                }
            }
        }

        return 0;
    }

    public function getShipments(int $orderId)
    {
        $httpClient = new Client();

        try {
        $a = $httpClient->request('GET', 'https://mysuperawesomestorewithstuffandthings.shopify.com/orders/shipments/' . $orderId);
        } catch (\Exception $e) {}

        $shipments = json_decode($a->getBody(), true);

        $shipment_info = [];

        foreach ($shipments as $shipment) {
            $shipment_info[] = $shipment['tracking_number'];
        }

        return $shipment_info;
    }
}
