<?php

namespace Arodiss\GithubBundle\Client;

use Github\Client;

class GithubClient {

	/** @var Client */
	protected $driver;

	/** @var string */
	protected $username;

	/** @var string */
	protected $repositoryOwner;

	/** @var string */
	protected $repositoryName;

	/**
	 * @param string $token
	 * @param string $username
	 * @param string $repositoryOwner
	 * @param string $repositoryName
	 */
	public function __construct(
		$token,
		$username,
		$repositoryOwner,
		$repositoryName
	) {
		$this->driver = new Client();
		$this->driver->authenticate($token, null, Client::AUTH_URL_TOKEN);
		$this->driver->setHeaders(array("User-Agent" => "PHP GitHub API (arodiss/GithubBundle)"));
		$this->username = $username;
		$this->repositoryOwner = $repositoryOwner;
		$this->repositoryName = $repositoryName;
	}

	/**
	 * @param string $prNumber
	 * @param string $comment
	 */
	public function commentPR($prNumber, $comment) {
		$this->getIssue()->comments()->create(
			$this->repositoryOwner,
			$this->repositoryName,
			$prNumber,
			array('body' => $comment)
		);
	}

	/**
	 * @param string $commit
	 * @param string $state
	 * @param string $targetUrl
	 * @param string $description
	 * @return bool
	 */
	public function updateCommitStatus($commit, $state, $targetUrl = "", $description = "") {
		$params = array("state" => $state);
		if(substr($targetUrl, 0, 5) == "https") {
			$params['target_url'] = $targetUrl;
		}
		if($description) {
			$params['description'] = $targetUrl;
		}
		$response = $this->driver
			->getHttpClient()
			->post(
				"repos/{$this->repositoryOwner}/{$this->repositoryName}/statuses/$commit",//todo real
				$params
			)
		;

		return $response->getStatusCode() === 201;
	}

	/**
	 * @return \Github\Api\PullRequest
	 */
	protected function getPR() {
		return $this->driver->api("pull_request");
	}

	/**
	 * @return \Github\Api\Issue
	 */
	protected function getIssue() {
		return $this->driver->api("issue");
	}
}
