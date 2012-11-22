<?php
namespace Fbrnc\Twitter\Command;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\Response;
use TYPO3\Flow\Utility\Files;

/**
 * Command controller for managing Twitter background tasks
 *
 * @Flow\Scope("singleton")
 */
class TwitterCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Fbrnc\Twitter\Service\TmhOAuth
	 * @Flow\Inject
	 */
	protected $twitterService;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var \Fbrnc\Twitter\Domain\Repository\TweetRepository
	 * @Flow\Inject
	 */
	protected $tweetRepository;

	/**
	 * @var \Fbrnc\Twitter\Domain\Repository\UserRepository
	 * @Flow\Inject
	 */
	protected $userRepository;

	/**
	 * @var \Fbrnc\Twitter\Domain\Repository\HashTagRepository
	 * @Flow\Inject
	 */
	protected $hashTagRespository;

	public function deleteTweetsCommand() {
		$this->tweetRepository->removeAll();
		$this->userRepository->removeAll();
		$this->hashTagRespository->removeAll();
	}

	/**
	 * Get Tweets from Twitter
	 *
	 * @throws \Exception
	 * @return void
	 */
	public function getTweetsCommand() {

		$this->twitterService->init();

		$apiUrl = $this->twitterService->url('1/statuses/home_timeline');

		$code = $this->twitterService->request('GET', $apiUrl, array(
		  'include_entities' => '1',
		  'include_rts'      => '1',
		  'screen_name'      => $this->configurationManager->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, "Twitter.Fbrnc.ScreeName"),
		  'count'            => 200,
		));

		if ($code != 200) {
			throw new \Exception('Error while fetching timeline. API url: ' . $apiUrl);
		}

		$timeline = json_decode($this->twitterService->response['response'], true);

		foreach ($timeline as $tweetData) { /* @var $tweetData array */

			$tweet = $this->tweetRepository->findByIdentifier($tweetData['id_str']);
			if ($tweet) { /* @var $tweet \Fbrnc\Twitter\Domain\Model\Tweet */
				if ($tweet->getImplicit()) {
					$tweet->setImplicit(false);
					$this->tweetRepository->update($tweet);
				}
				echo "Tweet already exists. Skipping.\n";
				continue;
			}

			$user = $this->userRepository->findByIdentifier($tweetData['user']['id_str']);

			if (!$user) {
				echo "+++ User {$tweetData['user']['screen_name']}\n";
				$user = new \Fbrnc\Twitter\Domain\Model\User();
				$user->setFromApiData($tweetData['user']);
				$this->userRepository->add($user);
			}

			echo "{$tweetData['text']}\n";

			$tweet = new \Fbrnc\Twitter\Domain\Model\Tweet();
			$tweet->setFromApiData($tweetData);

			$tweet->setUser($user);

			// hashtags
			if (!empty($tweetData['entities']['hashtags']) && is_array($tweetData['entities']['hashtags'])) {
				$hashTagsForThisTweet = array(); // avoiding duplicate hashtags (e.g. #hint #hint)
				foreach ($tweetData['entities']['hashtags'] as $mentionData) { /* @var $mentionData array */

					$tagText = trim(strtolower($mentionData['text']));
					if (empty($hashTagsForThisTweet[$tagText])) {
						echo "+++ Hashtag: {$tagText}\n";
						$hashTagsForThisTweet[$tagText] = 1;

						$hashTag = $this->hashTagRespository->findByIdentifier($tagText);

						if (!$hashTag) {
							$hashTag = new \Fbrnc\Twitter\Domain\Model\HashTag();
							$hashTag->setText($tagText);
							$this->hashTagRespository->add($hashTag);
						}

						$tweet->addHashTag($hashTag);
					} else {
						$hashTagsForThisTweet[$tagText]++;
					}
				}
			}

			// mentions
			if (!empty($tweetData['entities']['user_mentions']) && is_array($tweetData['entities']['user_mentions'])) {
				$mentionsForThisTweet = array(); // avoiding duplicate mentions
				foreach ($tweetData['entities']['user_mentions'] as $mentionData) { /* @var $mentionData array */

					$userId = trim(strtolower($mentionData['id_str']));
					if (empty($mentionsForThisTweet[$userId])) {
						$mentionsForThisTweet[$userId] = 1;

						$user = $this->userRepository->findByIdentifier($userId);

						if (!$user) {
							$user = new \Fbrnc\Twitter\Domain\Model\User();
							$user->setFromApiData($mentionData);
							$this->userRepository->add($user);
						}

						$tweet->addMention($user);
					} else {
						$mentionsForThisTweet[$userId]++;
					}
				}
			}

			$this->tweetRepository->add($tweet);
		}

	}

}

?>
