<?php

declare(strict_types=1);

namespace Devtoolcz\Discordtokenrevoke\Curl;

use Devtoolcz\Discordtokenrevoke\Exceptions\DiscordException;

class Client
{
	private string $api_url;

	private string $url;

	private string $token;

	private int $clientId;

	private string $clientSecretKey;

	public function __construct(string $api_url, string $url, int $clientId, string $clientSecretKey)
	{
		$this->api_url = $api_url;
		$this->url = $url;
		$this->clientId = $clientId;
		$this->clientSecretKey = $clientSecretKey;
	}

	public function send()
    {
        $cUrl = curl_init($this->api_url. $this->url);
        curl_setopt($cUrl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($cUrl, CURLOPT_HEADER, true);
        curl_setopt($cUrl, CURLOPT_POST, true);
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cUrl, CURLOPT_NOBODY, true);
        curl_setopt($cUrl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($cUrl, CURLOPT_POSTFIELDS, $this->getAccessData());

        curl_exec($cUrl);
        $httpcode = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);

        if ($httpcode >= 400)
            throw new DiscordException('Expected state code 200 but returned state code ' . $httpcode, $httpcode);

        curl_close($cUrl);
	}

	public function setToken(string $token) 
	{
        if(!is_string($token) || strlen($token) === 0) {
            throw new DiscordException('Token must be string but this is a null or unexpected value !', 405);
        }

		$this->token = $token;
		
		return $this;
    }

	protected function getHeaders() 
	{
        $headers = [
            'Authorization' => 'Basic ' . $this->getEncodedCredentials(),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return $headers;
    }

    protected function getToken() 
    {
        return $this->token;
    }

	protected function getAccessData() 
	{
        $data = [
            'token' => $this->getToken(),
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecretKey,
        ];
        return $data;
    }
    
    protected function getEncodedCredentials()
    {
        return base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecretKey));
    }
}