<?php
namespace Fbrnc\Twitter\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fbrnc.Twitter".         *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Hash tag
 *
 * @Flow\Entity
 */
class HashTag {

	/**
	 * The text
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 * @ORM\Id
	 * @Flow\Identity
	 */
	protected $text;


	/**
	 * Get the Hash tag's text
	 *
	 * @return string The Hash tag's text
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Sets this Hash tag's text
	 *
	 * @param string $text The Hash tag's text
	 * @return void
	 */
	public function setText($text) {
		$this->text = trim(strtolower($text));
	}

}
?>