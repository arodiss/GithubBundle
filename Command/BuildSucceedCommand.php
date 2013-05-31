<?php

namespace Arodiss\GithubBundle\Command;

use Arodiss\GithubBundle\CommitStatuses;
use Arodiss\GithubBundle\Phraser\MessageTypes;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildSucceedCommand extends AbstractGithubCommand {

	protected function configure() {
		$this
			->setName('ci:build:succeed')
			->setDefinition(array(
				new InputOption('pr', null, InputOption::VALUE_REQUIRED, 'Pull request number'),
				new InputOption('sha', null, InputOption::VALUE_REQUIRED, 'Last commit sha sum'),
				new InputOption('link', null, InputOption::VALUE_REQUIRED, 'Link to build'),
			))
			->setDescription('Mark build as successful')
		;
	}

	public function execute(InputInterface $input, OutputInterface $output) {
		$this->getClient()->commentPR(
			$input->getOption("pr"),
			$this->getPhraser()->getPhrase(MessageTypes::BUILD_OK_LONG)
		);

		$this->getClient()->updateCommitStatus(
			$input->getOption("sha"),
			CommitStatuses::SUCCESS,
			$input->getOption("link"),
			$this->getPhraser()->getPhrase(MessageTypes::BUILD_OK_SHORT)
		);
	}
}
