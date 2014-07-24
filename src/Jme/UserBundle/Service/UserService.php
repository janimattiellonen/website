<?php
namespace Jme\UserBundle\Service;

use Jme\Component\Service\AbstractBaseService;
use Jme\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserService extends AbstractBaseService
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @param Entitymanager             $em
     * @param SecurityContextInterface  $securityContext
     */
    public function __construct(
        EntityManager               $em,
        SecurityContextInterface    $securityContext)
    {
        parent::__construct($em);

        $this->securityContext  = $securityContext;
    }

    /**
     * @return User|string
     */
    public function getCurrentUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    public function isLoggedIn()
    {
        return is_object($this->getCurrentUser());
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function getUser($id)
    {
        return $this->userRepository->getUser($id);
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->userRepository->getUsers();
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getUsersFromIds(array $ids)
    {
        return $this->userRepository->getUsersFromIds($ids);
    }

    public function searchUsers($term)
    {
        return $this->userRepository->searchUsers($term);
    }
}
