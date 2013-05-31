# GithubBundle

Github integration for Symfony 2 projects, mostly useful for CI

## Installation

### via composer

Add repository to "repositories" section of your composer.json:
```
		{
			"type": "vcs",
			"url": "https://github.com/arodiss/GithubBundle.git"
		},
```

Add a dependency to "require" section of your composer.json:

```
	"arodiss/github-bundle": "dev-master",
```

Install new vendor

```
php bin/composer.phar update arodiss/github-bundle
```

## Configuration

Enable bundle in your AppKernel (we recommend to do it for test/dev environment only)

```PHP
//AppKernel::registerBundles()
if (in_array($this->getEnvironment(), array('dev', 'test'))) {
	...
	$bundles[] = new Arodiss\GithubBundle\ArodissGithubBundle();
}
```

Add configurations to your parameters.yml:

```
github_token: aaaa0000bbbb4444cccc2222
github_username: my-github-user
github_repository_owner: Vendor
github_repository_name: package
```

This will enable integration with repo "Vendor/package", and all requests will be signed by user "my-github-user". We recommend you to create separate account for it.

Wanna know how to get API token for the first line? Read this article: https://help.github.com/articles/creating-an-oauth-token-for-command-line-use

## Usage

You have now two extra commands in your Symfony CLI:

```
app/console ci:build:succeed --pr=5 --sha=e0182b0bf42ced85be9b3a615f6984ff56ff3110 --link=https:\\google.com
app/console ci:build:failed --pr=5 --sha=e0182b0bf42ced85be9b3a615f6984ff56ff3110 --link=https:\\google.com
```

This will update status of commit "e0182b0bf42ced85be9b3a615f6984ff56ff3110" and create comment in pull request number "5"

You can also include link to build, but it will be used only if it's HTTPS - GitHub API rejects everything else

## Customization

You can customize messages that will be posted to GitHub. For each event you can add unlimited number of messages and we will pick random when posting.
All event names are stored in class constants of MessageTypes

### Simpler way - run-time service configuration

```PHP
$container->get("github.phraser")->addPhrase(
    Arodiss\GithubBundle\Phraser\MessageTypes::BUILD_FAILED_LONG,
    "You failed it, dude"
);
```

### Better way - compile-time configuration via container params

```PHP
class AcmeExtension extends Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function load(array $configs, ContainerBuilder $containerBuilder) {
		//usually you do here something like that:
		//$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__.'/../Resources/config'));
		//$loader->load('services.yml');

		//add this to load file with your custom phrases
		$phpLoader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../Resources'));
		$phpLoader->load('phrases.php');
	}
}
```

In AcmeBundle\Resources\phrases.php you should have something similar to:

```PHP
/** @var $containerBuilder Symfony\Component\DependencyInjection\ContainerBuilder */
$containerBuilder->setParameter(
	'github.phraser.custom_phrases',
	array(
		\Arodiss\GithubBundle\Phraser\MessageTypes::BUILD_OK_LONG => array(
			"You did it!",
			"Your father could be proud"
		),
		\Arodiss\GithubBundle\Phraser\MessageTypes::BUILD_OK_SHORT => array(
			"OK",
		),
		\Arodiss\GithubBundle\Phraser\MessageTypes::BUILD_FAILED_LONG => array(
			"No good it is",
			"Smells like fail",
		),
		\Arodiss\GithubBundle\Phraser\MessageTypes::BUILD_FAILED_SHORT => array(
			"Failed",
		)
	)
);
```
