<?php
namespace Jme\MainBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert,
    Doctrine\ORM\Mapping as ORM,
    Symfony\Bridge\Doctrine\Validator\Constraints AS DoctrineAssert,
    FOS\UserBundle\Entity\User as BaseUser;

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
     * @Assert\MinLength(
     *     limit=3,
     *     message="The username must have at least {{ limit }} characters."
     * )
     * @Assert\MaxLength(
     *     limit=32,
     *     message="The username must not have more than {{ limit }} characters."
     * )
     */
    protected $username;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\MinLength(
     *     limit=4,
     *     message="Your password must have at least {{ limit }} characters."
     * )
     * @Assert\MaxLength(
     *     limit=255,
     *     message="Your password must not have more than {{ limit }} characters."
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
