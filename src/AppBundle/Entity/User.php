<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="text")
     */
    private $biography;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload your profile picture.")
     * @Assert\Image()
     */
    private $image;

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
     * Set name
     *
     * @param string $name
     *
     * @return user
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set biography
     *
     * @param string $biography
     *
     * @return user
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return user
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @ORM\OneToMany(targetEntity="Hobby", mappedBy="user", cascade={"persist",  "remove"}, orphanRemoval=true)
     */
    private $hobbies;


    public function __construct()
    {
        $this->hobbies = new ArrayCollection();
    }

    /**
     * Get hobby
     *
     * @return string
     */
    public function getHobby()
    {
        return $this->hobbies;
    }

    /**
     * Set hobby
     *
     * @param string $hobby
     *
     * @return user
     */
    public function setHobby($hobby)
    {
        $this->hobbies = $hobby;

        return $this;
    }

    public function addHobby(Hobby $hobby)
    {
        $hobby->setUser($this);
        $this->hobbies->add($hobby);
    }

    public function removeHobby(Hobby $hobby)
    {
        $this->hobbies->removeElement($hobby);
        $hobby->setUser(null);
    }

}
