<?php
namespace Jme\ArticleBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert,
    Doctrine\ORM\Mapping as ORM,
    Symfony\Bridge\Doctrine\Validator\Constraints AS DoctrineAssert;

/**
 * @ORM\Entity
 * @ORM\Table(name="article")
 */
class Article
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
     *  @ORM\Column(name="title", type="string", length=128, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\MinLength(
     *     limit=3,
     *     message="The title must have at least {{ limit }} characters."
     * )
     * @Assert\MaxLength(
     *     limit=128,
     *     message="The title must not have more than {{ limit }} characters."
     * )
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\MinLength(
     *     limit=3,
     *     message="The title must have at least {{ limit }} characters."
     * )
     */
    protected $content;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


}
