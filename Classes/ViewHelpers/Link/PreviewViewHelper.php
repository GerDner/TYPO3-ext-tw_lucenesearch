<?php

namespace Tollwerk\TwLucenesearch\ViewHelpers\Link;

/***************************************************************
 *  Copyright notice
 *
 *  © 2014 Dipl.-Ing. Joschi Kuphal <joschi@tollwerk.de>, tollwerk® GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Index page preview view helper
 * 
 * Renders a preview link to a particular page 
 * 
 * = Examples =
 *
 * <code title="Example">
 * <twlucene:link.preview pageUid="1" additionalParams="{document.referenceParameters}"/>
 * </code>
 *
 * Output:
 * A index preview link for the specified page
 *
 * @package		tw_lucenesearch
 * @copyright	Copyright © 2014 Dipl.-Ing. Joschi Kuphal <joschi@tollwerk.de>, tollwerk® GmbH (http://tollwerk.de)
 * @author		Dipl.-Ing. Joschi Kuphal <joschi@tollwerk.de>
 */
class PreviewViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\PageViewHelper {

	/**
	 * Renders a preview link to a particular page 
	 * 
	 * @param integer|NULL $pageUid							target page. See TypoLink destination
	 * @param array $additionalParams						query parameters to be attached to the resulting URI
	 * @param integer $pageType								type of the target page. See typolink.parameter
	 * @param boolean $noCache								set this to disable caching for the target page. You should not need this.
	 * @param boolean $noCacheHash							set this to supress the cHash query parameter created by TypoLink. You should not need this.
	 * @param string $section								the anchor to be added to the URI
	 * @param boolean $linkAccessRestrictedPages			If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.
	 * @param boolean $absolute								If set, the URI of the rendered link is absolute
	 * @param boolean $addQueryString						If set, the current query parameters will be kept in the URI
	 * @param array $argumentsToBeExcludedFromQueryString	arguments to be removed from the URI. Only active if $addQueryString = TRUE
	 * @return string Rendered page URI
	 */
	/**
	 * Renders a preview link to a particular page 
	 * 
	 * @param \array $reference								Reference parameters
	 * @see \TYPO3\CMS\Fluid\ViewHelpers\Link\PageViewHelper::render()
	 */
	public function render(array $reference) {
		if (array_key_exists('id', $reference)) {
			$pageUid			= $reference['id'];
			unset($reference['id']);
		} else {
			$pageUid			= 0;
		}
		if (array_key_exists('type', $reference)) {
			$pageType			= $reference['type'];
			unset($reference['type']);
		} else {
			$pageType			= 0;
		}
		$reference['index_content_only']					= 1;
		
		if (TYPO3_MODE === 'BE') {
			\Tollwerk\TwLucenesearch\Utility\FrontendSimulator::simulateFrontendEnvironment($pageUid);
		}
		
		$uriBuilder				= $this->controllerContext->getUriBuilder();
		$uri					= $uriBuilder
									->reset()
									->setTargetPageUid($pageUid)
									->setTargetPageType($pageType)
									->setNoCache(true)
									->setUseCacheHash(false)
									->setLinkAccessRestrictedPages(true)
									->setArguments($reference)
									->setCreateAbsoluteUri(true)
									->setAddQueryString(false)
									->setArgumentsToBeExcludedFromQueryString(array())
									->buildFrontendUri();
		if (strlen($uri)) {
			$this->tag->addAttribute('href', $uri);
			$this->tag->setContent($this->renderChildren());
			$result					= $this->tag->render();
		} else {
			$result					= $this->renderChildren();
		}
		
		if (TYPO3_MODE === 'BE') {
			\Tollwerk\TwLucenesearch\Utility\FrontendSimulator::resetFrontendEnvironment();
		}
		
		return $result;
	}
}

?>