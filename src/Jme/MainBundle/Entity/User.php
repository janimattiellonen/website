<?php
namespace Jme\MainBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert,
    Doctrine\ORM\Mapping as ORM,
    Symfony\Bridge\Doctrine\Validator\Constraints AS DoctrineAssert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @DoctrineAssert\UniqueEntity(fields="email")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=32, nullable=false)
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
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=false)
     * @Assert\NotNull()
     * @Assert\MinLength(
     *     limit=4,
     *     message="Your password must have at least {{ limit }} characters."
     * )
     * @Assert\MaxLength(
     *     limit=128,
     *     message="Your password must not have more than {{ limit }} characters."
     * )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=false)
     * @Assert\Email()
     * @Assert\NotNull()
     */
    private $email;

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     */
    public function __construct($username, $password, $email)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email    = $email;
    }

    /**
     * @param string $email
     *
     * @return UserEntity
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $password
     *
     * @return UserEntity
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $username
     *
     * @return UserEntity
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
