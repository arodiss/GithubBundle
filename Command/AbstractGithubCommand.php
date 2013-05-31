<?php

namespace Arodiss\GithubBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


abstract class AbstractGithubCommand extends ContainerAwareCommand {

	/**
	 * @return \Arodiss\GithubBundle\Phraser\Phraser
	 */
	protected function getPhraser() {
		return $this->getContainer()->get('github.phraser');
	}

	/**
	 * @return \Arodiss\GithubBundle\Client\GithubClient
	 */
	protected function getClient() {
		return $this->getContainer()->get('github.client');
	}
}
