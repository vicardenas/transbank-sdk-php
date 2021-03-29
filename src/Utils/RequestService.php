<?php

namespace Transbank\Utils;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Transbank\Webpay\Exceptions\TransbankApiRequest;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

class RequestService
{
    /**
     * @var ResponseInterface|null
     */
    protected $lastResponse = null;
    
    /**
     * @var TransbankApiRequest|null
     */
    protected $lastRequest = null;
    /**
     * @var HttpClient
     */
    protected $httpClient;
    
    public function __construct(HttpClient $httpClient = null)
    {
        $this->setHttpClient($httpClient !== null ? $httpClient : new HttpClient());
    }
   
    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }
    /**
     * @param $method
     * @param $endpoint
     * @param $payload
     * @param Options $options
     * @return array
     * @throws GuzzleException
     * @throws WebpayRequestException
     */
    public function request(
        $method,
        $endpoint,
        $payload,
        Options $options
    ) {
        $headers = $options->getHeaders();
        $client = $this->httpClient;
        if ($client == null) {
            $client = new HttpClient();
        }
        
        $baseUrl = $options->getApiBaseUrl();
        $request = new TransbankApiRequest($method, $baseUrl, $endpoint, $payload, $headers);
        
        $this->setLastRequest($request);
        $response = $client->perform($method, $baseUrl . $endpoint, $payload, ['headers' => $headers]);
        
        $this->setLastResponse($response);
        $responseStatusCode = $response->getStatusCode();
    
        if (!in_array($responseStatusCode, [200, 204])) {
            $reason = $response->getReasonPhrase();
            $message = "Could not obtain a response from Transbank API: $reason (HTTP code $responseStatusCode)";
            $body = json_decode($response->getBody(), true);
            $tbkErrorMessage = null;
            if (isset($body['error_message'])) {
                $tbkErrorMessage = $body['error_message'];
                $message = "Transbank API REST Error: $tbkErrorMessage | $message";
            }
            
            throw new WebpayRequestException($message, $tbkErrorMessage, $responseStatusCode, $request);
        }
        
        return json_decode($response->getBody(), true);
    }
    
    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
    
    /**
     * @param ResponseInterface|null $lastResponse
     */
    protected function setLastResponse($lastResponse)
    {
        $this->lastResponse = $lastResponse;
    }
    
    /**
     * @return TransbankApiRequest|null
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }
    
    /**
     * @param TransbankApiRequest|null $lastRequest
     */
    protected function setLastRequest($lastRequest)
    {
        $this->lastRequest = $lastRequest;
    }
}
