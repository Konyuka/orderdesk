<?php

namespace OrderDesk\App\Integration\WooCommerce;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient 
{
    private $httpClient;
    
    public function __construct() 
    {
        $this->httpClient = new Client();
    }
    
    public function getNumberOfOrders(): int
    {
        try {
            $response = $this->httpClient->request('GET', 'https://mysuperawesomestorewithstuffandthings.com/orders/list');  
        } catch (GuzzleException $e) {
             return 0;   
        }  
        
        $body = json_decode($response->getBody(), true);  
        
        if ($this->isValidResponse($body)) {
            return count($body['items']);       
        }
        
        return 0; 
    }
    
    public function getShipments(int $orderId): array 
    {
        try {
           $response = $this->httpClient->request('GET', "https://mysuperawesomestorewithstuffandthings.com/order/$orderId/shipments");         
        } catch (GuzzleException $e) {
             return [];   
        }        
        
        $shipments = json_decode($response->getBody(), true);
        
        return $this->extractTrackingNumbers($shipments);
    } 
    
    private function isValidResponse($response): bool
    {
        return isset($response['status']) 
             && $response['status'] === 'success';
    }
      
    private function extractTrackingNumbers($shipments): array
    {
        $trackingNumbers = [];
        
        foreach ($shipments as $shipment) {
            foreach ($shipment as $trackingNumber) {
                $trackingNumbers[] = $trackingNumber;
            }   
        }
          
        return $trackingNumbers;        
    } 
}