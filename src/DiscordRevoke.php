<?php 

declare(strict_types=1);

namespace Devtoolcz\Discordtokenrevoke;

use Devtoolcz\Discordtokenrevoke\Exceptions\DiscordException;
use Devtoolcz\Discordtokenrevoke\Interfaces\IRevokeRequest;

class DiscordRevoke implements IRevokeRequest
{

    /** @var string[] */
    private $config;

    /** @var string $token */
    private $token;

    public function __construct(array $config) 
    {
        $this->config = $config;
    }

    public function curlClient(string $url, array $headers, array $data)
    {
        $cUrl = curl_init($this->config['api_url']. $url);
        curl_setopt($cUrl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($cUrl, CURLOPT_HEADER, true);
        curl_setopt($cUrl, CURLOPT_POST, true);
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cUrl, CURLOPT_NOBODY, true);
        curl_setopt($cUrl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cUrl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($cUrl);
        $httpcode = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);

        if ($httpcode >= 400)
            return new DiscordException('Expected state code 200 but returned state code ' . $httpcode, $httpcode);

        return $response;

        curl_close($cUrl);
    }

    public function sendRevokeRequest(string $url)
    {
        return $this->curlClient($url, $this->getHeaders(), $this->getAccessData());
    }

    public function getHeaders() {
        $headers = [
            'Authorization' => 'Basic ' . $this->getEncodedCredentials(),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return $headers;
    }

    public function setToken(string $token) {
        $this->token = $token;
    }

    public function getToken() 
    {
        return $this->token;
    }

    public function getAccessData() {
        $data = [
            'token' => $this->getToken(),
            'client_id' => $this->config['clientId'],
            'client_secret' => $this->config['clientSecret'],
        ];
        return $data;
    }
    
    public function getEncodedCredentials()
    {
        return base64_encode(sprintf('%s:%s', $this->config['clientId'], $this->config['clientSecret']));
    }
    
}