<?php

namespace PointWeb\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * NewsletterMail
 *
 * @ORM\Table(name="newsletter_mail")
 * @ORM\Entity
 * @UniqueEntity("email", message="Cet email existe déjà")
 */
class NewsletterMail
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
     * @ORM\ManyToOne(targetEntity="NewsletterGroup", inversedBy="newsletterMail")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     **/
    private $newsletterGroup;

    /**
     * @var string
     * @Assert\Email(
     *     message = "l'email '{{ value }}' n'est pas valid.",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registerDate", type="date")
     */
    private $registerDate;


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
     * Set email
     *
     * @param string $email
     * @return NewsletterMail
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set registerDate
     *
     * @param \DateTime $registerDate
     * @return NewsletterMail
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * Get registerDate
     *
     * @return \DateTime 
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * Set newsletterGroup
     *
     * @param \PointWeb\NewsletterBundle\Entity\NewsletterGroup $newsletterGroup
     * @return NewsletterMail
     */
    public function setNewsletterGroup(\PointWeb\NewsletterBundle\Entity\NewsletterGroup $newsletterGroup = null)
    {
        $this->newsletterGroup = $newsletterGroup;

        return $this;
    }

    /**
     * Get newsletterGroup
     *
     * @return \PointWeb\NewsletterBundle\Entity\NewsletterGroup 
     */
    public function getNewsletterGroup()
    {
        return $this->newsletterGroup;
    }
}
