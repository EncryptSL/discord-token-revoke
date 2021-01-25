<?php 

declare(strict_types=1);

namespace Devtoolcz\Discordtokenrevoke;

use Devtoolcz\Discordtokenrevoke\Curl\Client;
use Devtoolcz\Discordtokenrevoke\Interfaces\IRevokeRequest;

class DiscordRevoke implements IRevokeRequest
{

    private array $config;

    public function __construct(array $config) 
    {
        $this->config = $config;
    }

    public function createRevokeRequest(string $url)
    {
        return new Client($this->config['api_url'], $url, (int) $this->config['clientId'], $this->config['clientSecret']);
    }
    
}