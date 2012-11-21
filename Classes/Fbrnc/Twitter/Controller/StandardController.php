<?php
namespace Fbrnc\Twitter\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fbrnc.Twitter".         *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the Fbrnc.Twitter package 
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('foos', array(
			'bar', 'bxaz'
		));
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function helloAction($name) {
		return 'HELLO ' . $name;
	}

}

?>