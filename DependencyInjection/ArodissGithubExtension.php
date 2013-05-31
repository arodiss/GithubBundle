<?php

namespace Arodiss\GithubBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ArodissGithubExtension extends Extension {
	/** {@inheritDoc} */
	public function load(array $configs, ContainerBuilder $containerBuilder) {
		$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.yml');

		if (false == $containerBuilder->hasDefinition('github.phraser')) {
			return;
		}

		if (false == $containerBuilder->hasParameter('github.phraser.custom_phrases')) {
			return;
		}

		$containerBuilder->getDefinition('github.phraser')->addMethodCall(
			'addPhrases',
			array($containerBuilder->getParameter('github.phraser.custom_phrases'))
		);
	}
}
