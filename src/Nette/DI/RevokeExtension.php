<?php

declare(strict_types=1);

namespace Devtoolcz\Discordtokenrevoke\Nette\DI;

use Devtoolcz\Discordtokenrevoke\DiscordRevoke;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
final class RevokeExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'clientId' => Expect::int()->required(),
			'clientSecret' => Expect::string()->required(),
			'api_url' => Expect::string()->default('https://discord.com/api/v8'),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$configData = [
			'clientId' => $config->clientId,
			'clientSecret' => $config->clientSecret,
			'api_url' => $config->api_url,
		];

		$builder->addDefinition($this->prefix('discordRevoke'))
			->setType(DiscordRevoke::class)
			->setArguments([$configData]);
	}
}
