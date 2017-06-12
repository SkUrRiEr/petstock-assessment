<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Article entity
 * 
 * @ORM\Entity
 * @Serializer\ExclusionPolicy("all")
 */
class Article {
    /**
     * @var integer Maximum amount of characters to show in the summary
     */
    const SUMMARY_LENGTH = 20;

    /**
     * @var int ID of the article
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     * @Serializer\Groups({"All"})
     */
    private $id;

    /**
     * @var int ID of the author of the article
     * @ORM\Column(type="integer")
     */
    private $author_id;

    /**
     * @var Author Author of this article
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="articles")
     */
    private $author;

    /**
     * @var string Name of the article
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose
     * @Serializer\Groups({"All"})
     */
    private $title;

    /**
     * @var string Article content
     * @ORM\Column(type="text")
     * @Serializer\Expose
     * @Serializer\Groups({"Detail"})
     */
    private $content;

    /**
     * @var DateTime Created date of the article
     * @ORM\Column(type="datetime")
     * @Serializer\Expose
     * @Serializer\Groups({"All"})
     */
    private $createdAt;

    /**
     * @var DateTime Last updated date of the article
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set authorId
     *
     * @param integer $authorId
     *
     * @return Article
     */
    public function setAuthorId($authorId)
    {
        $this->author_id = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer
     */
    public function getAuthorId()
    {
        return $this->author_id;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Article
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Article
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get URL
     *
     * @return string
     * 
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"All"})
     */
    public function getUrl()
    {
        return "/articles/" . $this->getId();
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\Author $author
     *
     * @return Article
     */
    public function setAuthor(\AppBundle\Entity\Author $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Returns the name of the author
     * 
     * @return string
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"All"})
     * @Serializer\SerializedName("author")
     */
    public function getAuthorName()
    {
        return $this->author->getName();
    }

    /**
     * Return a summary of the content
     *
     * @return string
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"Summary"})
     */
    public function getSummary()
    {
        if (strlen($this->content) > self::SUMMARY_LENGTH - 3) {
            return substr($this->content, 0, self::SUMMARY_LENGTH - 3) . "...";
        }

        return $this->content;
    }
}
