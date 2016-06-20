<?php

namespace PointWeb\GedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PointWeb\AdminBundle\Entity\File;
use PointWeb\UserBundle\Entity\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Folder
 *
 * @ORM\Table(name="folder")
 * @ORM\Entity(repositoryClass="PointWeb\GedBundle\Entity\FolderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Folder
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
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Ce champs ne peut pas être plus long que {{ limit }} caractères"
     * )
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     * @Assert\Range(min = "0")
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     *
     * @ORM\ManyToMany(targetEntity="PointWeb\AdminBundle\Entity\File", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="folder_file",
     *      joinColumns={@ORM\JoinColumn(name="folder_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    public $pictures;

    /**
     * @ORM\ManyToMany(targetEntity="PointWeb\UserBundle\Entity\User", inversedBy="folders")
     * @ORM\JoinTable(name="folders_users")
     **/
    public $users;

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
        $this->pictures = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Folder
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
     * Set description
     *
     * @param string $description
     * @return Folder
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
     * Set position
     *
     * @param string $position
     * @return Folder
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Add Pictures
     *
     * @param File $picture
     * @return Folder
     */
    public function addPicture(File $picture)
    {
        $this->pictures[] = $picture;

        return $this;
    }

    public function removePicture(File $picture)
    {
        $this->pictures->removeElement($picture);
    }

    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Add User
     *
     * @param User $user
     * @return Folder
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Folder
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
     * @return Folder
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
