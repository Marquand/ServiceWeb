<?php

namespace PointWeb\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * NewsletterGroup
 *
 * @ORM\Table(name="newsletter_group")
 * @ORM\Entity
 */
class NewsletterGroup
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
     * @ORM\OneToMany(targetEntity="NewsletterMail", mappedBy="newsletterGroup", cascade={"remove"})
     **/
    private $newsletterMail;

    /**
     * @ORM\OneToMany(targetEntity="Newsletter", mappedBy="newsletterGroup")
     **/
    private $newsletter;

    /**
     * @var string
     *
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
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="date")
     */
    private $createDate;



    public function __construct(){
        $this->newsletterMail = new ArrayCollection();
        $this->newsletter     = new ArrayCollection();
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
     * @return NewsletterGroup
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
     * @return NewsletterGroup
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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return NewsletterGroup
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
     * Add newsletterMail
     *
     * @param \PointWeb\NewsletterBundle\Entity\NewsletterGroup $newsletterMail
     * @return NewsletterGroup
     */
    public function addNewsletterMail(\PointWeb\NewsletterBundle\Entity\NewsletterGroup $newsletterMail)
    {
        $this->newsletterMail[] = $newsletterMail;

        return $this;
    }

    /**
     * Remove newsletterMail
     *
     * @param \PointWeb\NewsletterBundle\Entity\NewsletterGroup $newsletterMail
     */
    public function removeNewsletterMail(\PointWeb\NewsletterBundle\Entity\NewsletterGroup $newsletterMail)
    {
        $this->newsletterMail->removeElement($newsletterMail);
    }

    /**
     * Get newsletterMail
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNewsletterMail()
    {
        return $this->newsletterMail;
    }

    /**
     * Add newsletter
     *
     * @param \PointWeb\NewsletterBundle\Entity\Newsletter $newsletter
     * @return NewsletterGroup
     */
    public function addNewsletter(\PointWeb\NewsletterBundle\Entity\Newsletter $newsletter)
    {
        $this->newsletter[] = $newsletter;

        return $this;
    }

    /**
     * Remove newsletter
     *
     * @param \PointWeb\NewsletterBundle\Entity\Newsletter $newsletter
     */
    public function removeNewsletter(\PointWeb\NewsletterBundle\Entity\Newsletter $newsletter)
    {
        $this->newsletter->removeElement($newsletter);
    }

    /**
     * Get newsletter
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }
}
