<?php
namespace Jme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @DoctrineAssert\UniqueEntity(fields="email")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *     min=3,
     *     max=32,
     *     minMessage="The username must have at least {{ limit }} characters.",
     *     maxMessage="The username must not have more than {{ limit }} characters."
     * )
     */
    protected $username;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *     min=4,
     *     max=255,
     *     minMessage="Your password must have at least {{ limit }} characters.",
     *     maxMessage="Your password must not have more than {{ limit }} characters."
     * )
     */
    protected $password;

    /**
     * @var string
     *
     * @Assert\Email()
     * @Assert\NotNull()
     */
    protected $email;
}
