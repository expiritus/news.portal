<?php

namespace News\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Category
 *
 * @ORM\Table(name="div_category")
 * @ORM\Entity(repositoryClass="News\MainBundle\Entity\Repository\CategoryRepository")
 */
class Category
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
     * @Assert\Length(min=3, minMessage="Название категории должно быть не менее {{ limit }} символов")
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;


    /**
     * @ORM\OneToMany(targetEntity="Articles", mappedBy="category")
     */
    protected $articles;


    public function __construct(){
        $this->articles = new ArrayCollection();
    }

    public function __toString(){
        return $this->getCategory() ? $this->getCategory() : "";
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
     * Set category
     *
     * @param string $category
     * @return Category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add articles
     *
     * @param \News\MainBundle\Entity\Articles $articles
     * @return Category
     */
    public function addArticle(\News\MainBundle\Entity\Articles $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \News\MainBundle\Entity\Articles $articles
     */
    public function removeArticle(\News\MainBundle\Entity\Articles $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
