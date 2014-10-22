<?php
namespace Jme\MainBundle\Twig\Extensions;

use Twig_Environment;
use Twig_Extension;

class GoogleAnalytics extends Twig_Extension
{
	/**
	 * @var string
	 */
	private $code;

	/**
	 * @var bool
	 */
	private $enabled;

	public function __construct($code, $enabled = true)
	{
		$this->code	 	= $code;
		$this->enabled 	= $enabled;
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return array(
			'jme_ga' => new \Twig_Function_Method(
				$this, 'googleAnalytics', array('is_safe' => array('html') )
			),
		);
	}

	public function googleAnalytics()
	{
		if (!$this->enabled) {
			return '';
		}

		$qaHtml = "<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', '" . $this->code . "', 'auto');
			ga('send', 'pageview');
    	</script>";

		return $qaHtml;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'jme_google_analytics';
	}
}
 