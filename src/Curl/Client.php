<?php

declare(strict_types=1);

namespace Devtoolcz\Discordtokenrevoke\Curl;

use Devtoolcz\Discordtokenrevoke\Exceptions\DiscordException;

class Client
{
	/** @var string $api_url */
	private $api_url;

	/** @var string $url */
	private $url;

	/** @var string $token */
	private $token;

	/** @var int $clientId */
	private $clientId;

	/** @var string $clientSecretKey */
	private $clientSecretKey;

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

        $response = curl_exec($cUrl);
        $httpcode = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);

        if ($httpcode >= 400)
            throw new DiscordException('Expected state code 200 but returned state code ' . $httpcode, $httpcode);

        return bdump($response);

        curl_close($cUrl);
	}

	public function setToken(string $token) 
	{
		$this->token = $token;
		
		return $this;
    }

	public function getHeaders() 
	{
        $headers = [
            'Authorization' => 'Basic ' . $this->getEncodedCredentials(),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return $headers;
    }

    public function getToken() 
    {
        return $this->token;
    }

	public function getAccessData() 
	{
        $data = [
            'token' => $this->getToken(),
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecretKey,
        ];
        return $data;
    }
    
    public function getEncodedCredentials()
    {
        return base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecretKey));
    }


	

}