<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * hobbies
 *
 * @ORM\Table(name="hobbies")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\hobbiesRepository")
 */
class hobbies
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="userID", type="integer")
     */
    private $userID;

    /**
     * @var string
     *
     * @ORM\Column(name="hobby", type="string", length=255)
     */
    private $hobby;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetimetz")
     */
    private $date;


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
     * Set userID
     *
     * @param integer $userID
     *
     * @return hobbies
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;

        return $this;
    }

    /**
     * Get userID
     *
     * @return int
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Set hobby
     *
     * @param string $hobby
     *
     * @return hobbies
     */
    public function setHobby($hobby)
    {
        $this->hobby = $hobby;

        return $this;
    }

    /**
     * Get hobby
     *
     * @return string
     */
    public function getHobby()
    {
        return $this->hobby;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return hobbies
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
}
