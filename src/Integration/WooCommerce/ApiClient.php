<?php

namespace OrderDesk\App\Integration\WooCommerce;

use GuzzleHttp\Client;

class ApiClient
{
    public function __construct()
    {
        $this->http_client = new Client();
    }

    public function get_number_of_orders()
    {
        try {
            $a = $this->http_client->request('GET', 'https://mysuperawesomestorewithstuffandthings.com/orders/list');
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

    public function getShipments(int $orderId): array
    {
        try {
            $a = $this->http_client->request('GET', 'https://mysuperawesomestorewithstuffandthings.com/order/' . $orderId . '/shipments');
        } catch (\Exception $e) {}

        $shipments = json_decode($a->getBody(), true);

        $shipment_info = [];

        foreach ($shipments as $shipment) {
            foreach ($shipment as $trackingNumber) {
                $shipment_info[] = $trackingNumber;
            }
        }

        return $shipment_info;
    }
}
