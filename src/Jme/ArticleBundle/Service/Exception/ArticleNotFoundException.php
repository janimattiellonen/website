<?php
namespace Jme\ArticleBundle\Service\Exception;

use \Exception;

class ArticleNotFoundException extends ArticleException
{
    /**
     * @param Exception $previous
     */
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Article was not found', null, $previous);
    }
}
