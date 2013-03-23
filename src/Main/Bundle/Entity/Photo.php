<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Main\Bundle\Entity\Branch;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Photo
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
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="photos")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id")
     */
    private $branch;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

    /**
     * @var string
     *
     * @ORM\Column(name="alt_teg", type="string", length=255)
     */
    private $alt_teg;

    /**
     * @Vich\UploadableField(mapping="photo_pizzeria", fileNameProperty="imageName")
     *
     * @var File $image
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, name="image_name", nullable=true)
     *
     * @var string $imageName
     */
    protected $imageName;

    public function __construct($lang = 'ru')
    {
        $this->updated_at = new \DateTime();
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
     * Set branch
     *
     * @param Branch $branch
     */
    public function setBranch(Branch $branch)
    {
        $this->branch = $branch;
    }

    /**
     * Get branch
     *
     * @return Branch
     */
    public function getBranch()
    {

        return $this->branch;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Photo
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Photo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set alt_teg
     *
     * @param string $altTeg
     * @return Photo
     */
    public function setAltTeg($altTeg)
    {
        $this->alt_teg = $altTeg;
    
        return $this;
    }

    /**
     * Get alt_teg
     *
     * @return string 
     */
    public function getAltTeg()
    {
        return $this->alt_teg;
    }

    /**
     * Set imageName
     *
     * @param string $url
     * @return Chain
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImage($image)
    {
        $this->image = $image;

        if ($image instanceof UploadedFile) {
            $this->updated_at = new \DateTime();
        }
    }

    public function getImage()
    {
        return $this->image;
    }

}
