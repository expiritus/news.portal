<?php

namespace News\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OnLine
 *
 * @ORM\Table(name="div_online")
 * @ORM\Entity(repositoryClass="News\MainBundle\Entity\Repository\OnlineRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Online
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
     * @ORM\Column(name="user_ip", type="string", length=255)
     */
    private $user_ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_visit", type="datetime")
     */
    protected $lastVisit;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
//    public function setLastVisitValue(){
//        $this->lastVisit = new \DateTime();
//    }

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
     * Set ip
     *
     * @param string $ip
     * @return OnLine
     */
    public function setUserIp($ip)
    {
        $this->user_ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getUserIp()
    {
        return $this->user_ip;
    }

    /**
     * Set lastVisit
     *
     * @param \DateTime $lastVisit
     * @return OnLine
     */
    public function setLastVisit($lastVisit)
    {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    /**
     * Get lastVisit
     *
     * @return \DateTime 
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }
}
