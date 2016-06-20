<?php

namespace PointWeb\ReferencingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Referencing
 *
 * @ORM\Table(name="referencing")
 * @ORM\Entity(repositoryClass="PointWeb\ReferencingBundle\Entity\ReferencingRepository")
 */
class Referencing
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="h1", type="string", length=255)
     */
    private $h1;

    /**
     * @var string
     *
     * @ORM\Column(name="h1_title", type="string", length=255)
     */
    private $h1Title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var boolean
     * @ORM\Column(name="online", type="boolean", nullable=true)
     */
    private $online = true;

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
     * @return Referencing
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
     * Set h1
     *
     * @param string $h1
     * @return Referencing
     */
    public function setH1($h1)
    {
        $this->h1 = $h1;

        return $this;
    }

    /**
     * Get h1
     *
     * @return string 
     */
    public function getH1()
    {
        return $this->h1;
    }

    /**
     * Set h1Title
     *
     * @param string $h1Title
     * @return Referencing
     */
    public function setH1Title($h1Title)
    {
        $this->h1Title = $h1Title;

        return $this;
    }

    /**
     * Get h1Title
     *
     * @return string 
     */
    public function getH1Title()
    {
        return $this->h1Title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Referencing
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Referencing
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }



    /**
     * Set online
     *
     * @param boolean $online
     * @return Referencing
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getOnline()
    {
        return $this->online;
    }
}
