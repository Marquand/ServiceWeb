<?php

namespace PointWeb\PdfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Pdf
 *
 * @ORM\Table(name="pdf")
 * @ORM\Entity(repositoryClass="PointWeb\PdfBundle\Entity\PdfRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Pdf
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
     * @ORM\Column(name="pdf_name", type="string", length=255)
     */
    private $pdfName;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf_file", type="string", length=255)
     */
    private $pdfFile;

    /**
     * @var File
     */
    public $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="date")
     */
    private $createDate;

    /**
     * @var string
     * @ORM\Column(name="target", type="string", length=255)
     */
    private $target;


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
     * Set pdfName
     *
     * @param string $pdfName
     * @return Pdf
     */
    public function setPdfName($pdfName)
    {
        $this->pdfName = $pdfName;

        return $this;
    }

    /**
     * Get pdfName
     *
     * @return string
     */
    public function getPdfName()
    {
        return $this->pdfName;
    }

    /**
     * Set pdfFile
     *
     * @param string $pdfFile
     * @return Pdf
     */
    public function setPdfFile($pdfFile)
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }

    /**
     * Get pdfFile
     *
     * @return string
     */
    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Pdf
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
     * @return string
     */
    public function directoryPath(){
        return dirname(dirname(dirname(dirname(__DIR__))))."/web/upload/pdf";
    }


    /**
     * @ORM\PrePersist()
     */
    public function createDate(){
        $this->createDate = new \DateTime();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadPdf(){
        if($this->file instanceof UploadedFile){
            $extension = array('pdf');
            if(in_array($this->file->guessExtension(), $extension))
            {
                $filename = md5(uniqid($this->file->getClientOriginalName())).".".$this->file->guessExtension();
                $this->file->move($this->directoryPath(), $filename);
                $this->pdfFile = $filename;
            }
        }
    }

    public function getPdfPath($base){
        return $base."upload/pdf/".$this->pdfFile;
    }

    /**
     * Set target
     *
     * @param string $target
     * @return Pdf
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
