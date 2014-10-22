<?php
namespace Jme\ArticleBundle\Entity;

use \DateTime;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\Taggable\Taggable;

use Gedmo\Mapping\Annotation as Gedmo;

use Jme\TagBundle\Entity\Tag;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints AS DoctrineAssert;

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
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    protected $published = false;

    /**
     * @var string
     *
     *  @ORM\Column(name="title", type="string", length=128, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *     min=3,
     *     max=128,
     *     minMessage="The title must have at least {{ limit }} character.|The title must have at least {{ limit }} characters.",
     *     maxMessage="The title must not have more than {{ limit }} character.|The title must not have more than {{ limit }} characters."
     * )
     */
    protected $title;

    /**
     * @var string
     *
     *  @ORM\Column(name="brief", type="string", length=500, nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *     min=3,
     *     max=500,
     *     minMessage="The brief must have at least {{ limit }} character.|The brief must have at least {{ limit }} characters.",
     *     maxMessage="The brief must not have more than {{ limit }} character.|The brief must not have more than {{ limit }} characters."
     * )
     */
    protected $brief;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *     min=3,
     *     minMessage="The content must have at least {{ limit }} character.|The content must have at least {{ limit }} characters."
     * )
     */
    protected $content;

    /**
     * @var ArrayCollection
     */
    protected $tags;

    /**
     * @Gedmo\Slug(fields={"title"}, unique=false, updatable=true)
     * @ORM\Column(length=128, unique=false)
     */
    protected $slug;

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

    /**
     * @param $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getUrlId()
    {
        $slug = $this->getSlug();

        return strlen($slug) > 0 ? $slug : $this->getId();
    }

    /**
     * @param boolean $published
     *
     * @return Article
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->getPublished();
    }

}
