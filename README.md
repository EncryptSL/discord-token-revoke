### devtoolscz/discordtokenrevoke

**Introduction**
Discord Revoke token is a small library for revoking token.
Example when user logout from page, we must revoke old token, for preventing leaking tokens.

**Setup**
Recommended way to install is via composer.
> composer require devtoolcz/discordtokenrevoke

```yaml
extensions:
	revoke: Devtoolcz\Discordtokenrevoke\Nette\DI\RevokeExtension
```

**Configuration**
```yaml
revoke:
    clientId: your discord application id
    clientSecret: your discord application secret key
    api_url: 'https://discord.com/api/v8'
```
**Usage**
```php
use Nette;
use Devtoolcz\DiscordRevoke;

final class ExamplePresenter extends Nette\Application\UI\Presenter
{

    /** @var DiscordRevoke @inject */
    private $discordRevoke;

    public function actionDiscordLogout()
    {
        $this->discordRevoke->setToken($this->user->identity->token);
        $this->discordRevoke->sendRevokeRequest('/oauth2/token/revoke');
    }
}
```
