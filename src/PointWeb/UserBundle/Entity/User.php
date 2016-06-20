<?php

namespace PointWeb\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use PointWeb\GedBundle\Entity\Folder;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="PointWeb\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur est déjà utilisé")
 * @UniqueEntity(fields="email", message="L'adresse mail est déjà utilisée")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Regex(
     *     pattern="/[a-zA-Z0-9]/",
     *     match=true,
     *     message="Le nom d'utilisateur ne peut contenir que des majuscules/minuscules et des chiffres, sans espaces"
     * )
     */
    protected $username;

    /**
     * @ORM\Column(type="string", name="first_name", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", name="last_name", length=255)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", name="color", length=255, nullable=true)
     */
    protected $color;

    /**
     * @ORM\ManyToMany(targetEntity="PointWeb\GedBundle\Entity\Folder", mappedBy="users")
     * @ORM\OrderBy({"createDate" = "DESC"})
     **/
    private $folders;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="date")
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date")
     */
    private $createDate;

    public function __construct()
    {
        parent::__construct();
        $this->folders = new ArrayCollection();
        $this->enabled = 0;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function getFullname()
    {
        return $this->username . ' (' . $this->lastName . ' ' . $this->firstName . ')';
    }

    /**
     * Add Folder
     *
     * @param Folder $folder
     * @return User
     */
    public function addFolder(Folder $folder)
    {
        $this->folders[] = $folder;

        return $this;
    }

    public function removeFolder(Folder $folder)
    {
        $this->folders->removeElement($folder);
    }

    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return User
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return User
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        if (null == $this->createDate) {
            $this->createDate = new \DateTime();
        }
        $this->updateDate = new \DateTime();
    }
}