<?php
namespace Jme\CommentBundle\Controller;

use FOS\CommentBundle\Controller\ThreadController as BaseThreadController;

use Symfony\Component\HttpFoundation\Request;

class ThreadController extends BaseThreadController
{
    /**
     * Get the comments of a thread. Creates a new thread if none exists.
     *
     * @param Request $request Current request
     * @param string  $id      Id of the thread
     *
     * @return View
     * @todo Add support page/pagesize/sorting/tree-depth parameters
     */
    public function getThreadCommentsAction(Request $request, $id)
    {
        return parent::getThreadCommentsAction($request, $id);
    }
}
