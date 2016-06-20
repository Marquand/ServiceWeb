<?php

namespace PointWeb\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Newsletter
 *
 * @ORM\Table(name="newsletter")
 * @ORM\Entity
 */
class Newsletter
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
     * @ORM\ManyToOne(targetEntity="NewsletterGroup", inversedBy="newsletter")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     **/
    private $newsletterGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=255)
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="addresser", type="string", length=255)
     */
    private $addresser;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendDate", type="date")
     */
    private $sendDate;


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
     * Set object
     *
     * @param string $object
     * @return Newsletter
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string 
     */
    public function getObject()
    {
        return $this->object;
    }


    /**
     * Set addresser
     *
     * @param string $addresser
     * @return Newsletter
     */
    public function setAddresser($addresser)
    {
        $this->addresser = $addresser;

        return $this;
    }

    /**
     * Get addresser
     *
     * @return string 
     */
    public function getAddresser()
    {
        return $this->addresser;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Newsletter
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set sendDate
     *
     * @param \DateTime $sendDate
     * @return Newsletter
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * Get sendDate
     *
     * @return \DateTime 
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Set newsletterGroup
     *
     * @param \PointWeb\NewsletterBundle\Entity\NewsletterGroup $newsletterGroup
     * @return Newsletter
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
