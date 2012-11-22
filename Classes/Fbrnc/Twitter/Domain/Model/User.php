<?php
namespace Fbrnc\Twitter\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A user
 *
 * @Flow\Entity
 */
class User {

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 * @ORM\Id
	 * @Flow\Identity
	 */
	protected $id;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $screenName;

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $screenName
	 */
	public function setScreenName($screenName) {
		$this->screenName = $screenName;
	}

	/**
	 * @return string
	 */
	public function getScreenName() {
		return $this->screenName;
	}

	/**
	 * Set values from API data
	 *
	 * @param array $userData
	 */
	public function setFromApiData(array $userData) {
		$this->setId($userData['id_str']);
		$this->setName($userData['name']);
		$this->setScreenName($userData['screen_name']);
	}

}

?>