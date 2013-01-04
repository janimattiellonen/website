<?php

namespace Jme\CommentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JmeCommentBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSCommentBundle';
    }
}
