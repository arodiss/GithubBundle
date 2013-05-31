<?php

namespace Arodiss\GithubBundle\Phraser;

class Phraser {

	/**
	 * @var array|array[]|string[][]
	 */
	protected $customPhrases;

	/**
	 * @var array|array[]|string[][]
	 */
	protected $defaultPhrases = array(
		MessageTypes::BUILD_OK_SHORT           => array("built successfully"),
		MessageTypes::BUILD_FAILED_SHORT       => array("build failed"),
		MessageTypes::BUILD_OK_LONG            => array("built successfully"),
		MessageTypes::BUILD_FAILED_LONG        => array("build failed"),
	);

	/**
	 * @param string $type
	 * @param string $phrase
	 */
	public function addPhrase($type, $phrase) {
		if(false === isset($this->customPhrases[$type])) {
			$this->customPhrases[$type] = array();
		}

		$this->customPhrases[$type][] = $phrase;
	}

	/**
	 * @param array $phrases
	 */
	public function addPhrases(array $phrases) {
		foreach($phrases as $type => $phrasesInType) {
			if(is_string($phrasesInType)) {
				$this->addPhrase($type, $phrasesInType);
			} elseif(is_array($phrasesInType)) {
				foreach($phrasesInType as $phraseInType) {
					$this->addPhrase($type, $phraseInType);
				}
			}
		}
	}

	/**
	 * @param string $type
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function getPhrase($type) {
		if(isset($this->customPhrases[$type])) {
			$phrases = $this->customPhrases[$type];
		} elseif(isset($this->defaultPhrases[$type])) {
			$phrases = $this->defaultPhrases[$type];
		} else {
			throw new \InvalidArgumentException("Sorry, man, I don't know what is `$type` you want to talk about");
		}

		return $phrases[rand(0, count($phrases))];
	}
}
