<?php

namespace SoftUniBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="SoftUniBlogBundle\Repository\ArticleRepository")
 * @ExclusionPolicy("all")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     *
     */
    private $title;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     */
    private $summary;

    /**
     * @ORM\Column(name="authorId", type="integer")
     */
    private $authorId;

    /**
     * @ORM\ManyToOne(targetEntity="SoftUniBlogBundle\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $author;


    public function __construct()
    {
        $this->date = new \DateTime("now");
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
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
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @var string
     */
    public function setSummary()    {

        $this->summary = substr($this->getContent(), 0, strlen($this->getContent()) /2 ).'...';

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()    {


        if(null === $this->summary)    {
            $this->setSummary();
        }

        return $this->summary;
    }

    /**
     * @param $authorId
     * @return $this
     */
    public function setAuthorId($authorId)  {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return integer
     */
    public function getAuthorId()   {
        return $this->authorId;
    }

    /**
     * @param \SoftUniBlogBundle\Entity\User $author
     * @return $this
     */
    public function setAuthor(User $author) {
        $this->author = $author;
        return $this;
    }

    /**
     * @return \SoftUniBlogBundle\Entity\User
     */
    public function getAuthor() {
        return $this->author;
    }
}

