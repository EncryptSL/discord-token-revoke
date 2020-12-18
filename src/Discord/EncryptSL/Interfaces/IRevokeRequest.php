<?php

declare(strict_types=1);

namespace Discord\EncryptSL\Interfaces;

interface IRevokeRequest {
   public function curlClient(string $url, array $headers, array $data);
   public function getEncodedCredentials();
   public function sendRevokeRequest(string $url);
   public function getHeaders();
}
