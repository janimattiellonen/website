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
