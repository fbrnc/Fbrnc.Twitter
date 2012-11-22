<?php
namespace Fbrnc\Twitter\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A tweet
 *
 * @Flow\Entity
 */
class Tweet {

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
	protected $text;

	/**
	 * @var \Fbrnc\Twitter\Domain\Model\User
	 * @ORM\ManyToOne(cascade={"persist"})
	 */
	protected $user;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Fbrnc\Twitter\Domain\Model\User>
	 * @ORM\ManyToMany(cascade={"persist"})
	 */
	protected $mentions;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Fbrnc\Twitter\Domain\Model\HashTag>
	 * @ORM\ManyToMany
	 */
	protected $hashTags;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $raw;

	/**
	 * @var string
	 */
	protected $source;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $created_at;

	/**
	 * @var \Fbrnc\Twitter\Domain\Model\Tweet
	 * @ORM\ManyToOne(cascade={"persist"})
	 */
	protected $retweeted_status;

	/**
	 * Reference to another tweet. Only pointing to id because the tweet might not be present
	 *
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $in_reply_to_status_id;

	/**
	 * If this tweet is not on current timelime, but referenced (e.g. as a retweet or reply)
	 *
	 * @var bool
	 */
	protected $implicit = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hashTags = new \Doctrine\Common\Collections\ArrayCollection();
		$this->mentions = new \Doctrine\Common\Collections\ArrayCollection();
	}

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
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @param \Fbrnc\Twitter\Domain\Model\User $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @return \Fbrnc\Twitter\Domain\Model\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $hashTags
	 */
	public function setHashTags($hashTags) {
		$this->hashTags = $hashTags;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getHashTags() {
		return $this->hashTags;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $mentions
	 */
	public function setMentions($mentions) {
		$this->mentions = $mentions;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getMentions() {
		return $this->mentions;
	}

	public function addMention(\Fbrnc\Twitter\Domain\Model\User $user) {
		$this->mentions->add($user);
	}

	public function addHashTag(\Fbrnc\Twitter\Domain\Model\HashTag $hashTag) {
		$this->hashTags->add($hashTag);
	}

	/**
	 * @param string $raw
	 */
	public function setRaw($raw) {
		$this->raw = $raw;
	}

	/**
	 * @return string
	 */
	public function getRaw() {
		return $this->raw;
	}

	/**
	 * @param \DateTime $created_at
	 */
	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * @param string $source
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	/**
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param boolean $implicit
	 */
	public function setImplicit($implicit) {
		$this->implicit = $implicit;
	}

	/**
	 * @return boolean
	 */
	public function getImplicit() {
		return $this->implicit;
	}

	/**
	 * @param string $in_reply_to_status_id
	 */
	public function setInReplyToStatusId($in_reply_to_status_id) {
		$this->in_reply_to_status_id = $in_reply_to_status_id;
	}

	/**
	 * @return string
	 */
	public function getInReplyToStatusId() {
		return $this->in_reply_to_status_id;
	}

	/**
	 * @param \Fbrnc\Twitter\Domain\Model\Tweet $retweeted_status
	 */
	public function setRetweetedStatus($retweeted_status) {
		$this->retweeted_status = $retweeted_status;
	}

	/**
	 * @return \Fbrnc\Twitter\Domain\Model\Tweet
	 */
	public function getRetweetedStatus() {
		return $this->retweeted_status;
	}

	/**
	 * Set from API data
	 *
	 * @param array $tweetData
	 */
	public function setFromApiData(array $tweetData) {
		$this->setRaw(serialize($tweetData));
		$this->setId($tweetData['id_str']);
		$this->setText($tweetData['text']);
		$this->setSource($tweetData['source']);
		$this->setCreatedAt(new \DateTime($tweetData['created_at']));
	}

}

?>