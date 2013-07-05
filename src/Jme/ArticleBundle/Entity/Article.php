<?php
namespace Jme\ArticleBundle\Entity;

use \DateTime,
    Symfony\Component\Validator\Constraints as Assert,
    Doctrine\ORM\Mapping as ORM,
    Symfony\Bridge\Doctrine\Validator\Constraints AS DoctrineAssert,
    Gedmo\Mapping\Annotation as Gedmo,
    Xi\Bundle\TagBundle\Entity\Tag,
    Doctrine\Common\Collections\ArrayCollection,
    DoctrineExtensions\Taggable\Taggable;

/**
 * @ORM\Entity
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="Jme\ArticleBundle\Repository\ArticleRepository")
 */
class Article implements Taggable
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
     * @var DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @var string
     *
     *  @ORM\Column(name="title", type="string", length=128, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\MinLength(
     *     limit=3,
     *     message="The title must have at least {{ limit }} character.|The title must have at least {{ limit }} characters."
     * )
     * @Assert\MaxLength(
     *     limit=128,
     *     message="The title must not have more than {{ limit }} character.|The title must not have more than {{ limit }} characters."
     * )
     */
    protected $title;

    /**
     * @var string
     *
     *  @ORM\Column(name="brief", type="string", length=500, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\MinLength(
     *     limit=3,
     *     message="The brief must have at least {{ limit }} character.|The brief must have at least {{ limit }} characters."
     * )
     * @Assert\MaxLength(
     *     limit=500,
     *     message="The brief must not have more than {{ limit }} character.|The brief must not have more than {{ limit }} characters."
     * )
     */
    protected $brief;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\MinLength(
     *     limit=3,
     *     message="The content must have at least {{ limit }} character.|The content must have at least {{ limit }} characters."
     * )
     */
    protected $content;

    /**
     * @var ArrayCollection
     */
    protected $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
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

    /**
     * @param string $brief
     *
     * @return Article
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * @return string
     */
    public function getBrief()
    {
        return $this->brief;
    }

    /**
     * Returns the unique taggable resource type
     *
     * @return string
     */
    public function getTaggableType()
    {
        return 'article';
    }

    /**
     * Returns the unique taggable resource identifier
     *
     * @return string
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * Returns the collection of tags for this Taggable entity
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    /**
     * @param Tag $tag
     * @return bool
     */
    public function hasTag(Tag $tag)
    {
        return $this->getTags()->contains($tag);
    }

    /**
     * @param array $tags
     * @return Article
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}
