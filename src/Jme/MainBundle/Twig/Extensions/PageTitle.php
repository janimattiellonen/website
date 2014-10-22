<?php
namespace Jme\MainBundle\Twig\Extensions;

use Twig_Environment;
use Twig_Extension;

class PageTitle extends Twig_Extension
{
	/**
	 * @var string
	 */
	private $siteName;

	public function __construct($siteName)
	{
		$this->siteName = $siteName;
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return array(
			'jme_page_title' => new \Twig_Function_Method(
				$this, 'pageTitle', array('is_safe' => array('html') )
			),
		);
	}

	public function pageTitle($text)
	{
		return strlen(trim($text)) > 0 ? trim($text) . " | {$this->siteName}" : $this->siteName;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'jme_page_title';
	}
}