<?php

declare(strict_types=1);

namespace Devtoolcz\Discordtokenrevoke\Interfaces;

interface IRevokeRequest {
   public function createRevokeRequest(string $url);
}
