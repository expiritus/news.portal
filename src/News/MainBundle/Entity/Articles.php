<?php

namespace News\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Articles
 *
 * @ORM\Table(name="div_articles")
 * @ORM\Entity(repositoryClass="News\MainBundle\Entity\Repository\ArticlesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Articles
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
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     * @Assert\Length(min=3, minMessage="Заголовок должен быть не менее {{ limit }} символов")
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;


    /**
     * @var string
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     * @Assert\Length(min=3, max=255, minMessage="Текст должен быть не менее {{ limit }} символов", maxMessage="Текст должен быть не длиннее {{ limit }} символов")
     * @ORM\Column(name="short_text", type="string", length=255)
     */
    private $short_text;

    /**
     * @var string
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     * @ORM\Column(name="articles", type="text")
     */
    private $articles;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255)
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;


    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string")
     */
    private $userName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Поле не должно быть пустым")
     */
    protected $category;


    /**
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="articles")
     */
    protected $comments;


    public function __construct(){
        $this->comments = new ArrayCollection();
    }

    public function __toString(){
        return $this->getTitle() ? $this->getTitle() : "";
    }

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
     * Set title
     *
     * @param string $title
     * @return Articles
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
     * Set article
     *
     * @param string $article
     * @return Articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;

        return $this;
    }

    /**
     * Get article
     *
     * @return string 
     */
    public function getArticle()
    {
        return $this->articles;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     * @return Articles
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Articles
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(){
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue(){
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Articles
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
     * Get articles
     *
     * @return string 
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Set category
     *
     * @param \News\MainBundle\Entity\Category $category
     * @return Articles
     */
    public function setCategory(\News\MainBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \News\MainBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add comments
     *
     * @param \News\MainBundle\Entity\Comments $comments
     * @return Articles
     */
    public function addComment(\News\MainBundle\Entity\Comments $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \News\MainBundle\Entity\Comments $comments
     */
    public function removeComment(\News\MainBundle\Entity\Comments $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return Articles
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }


    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Articles
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set short_text
     *
     * @param string $shortText
     * @return Articles
     */
    public function setShortText($shortText)
    {
        $this->short_text = $shortText;

        return $this;
    }

    /**
     * Get short_text
     *
     * @return string 
     */
    public function getShortText()
    {
        return $this->short_text;
    }
}
