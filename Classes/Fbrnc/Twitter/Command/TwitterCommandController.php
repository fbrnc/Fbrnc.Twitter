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
	 * Get Tweets from Twitter
	 *
	 * @return void
	 */
	public function getTweetsCommand() {

		$this->twitterService->init();
		$code = $this->twitterService->request('GET', $this->twitterService->url('1/statuses/home_timeline'), array(
		  'include_entities' => '1',
		  'include_rts'      => '1',
		  'screen_name'      => 'fbrnc',
		  'count'            => 10,
		));

		if ($code == 200) {
			$timeline = json_decode($this->twitterService->response['response'], true);
			var_dump($timeline);
		}
		var_dump($code);
	}

}

?>
