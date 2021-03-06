<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Main\Bundle\Entity\Chain;

/**
 * Photo
 *
 * @ORM\Table(name="photo_delivery")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class PhotoDelivery
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
     * @ORM\ManyToOne(targetEntity="Chain", inversedBy="photos")
     * @ORM\JoinColumn(name="chain_id", referencedColumnName="id")
     */
    private $chain;

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
     * @Vich\UploadableField(mapping="photo_pizzeria_delivery", fileNameProperty="imageName")
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

    public function __construct()
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
     * Set chain
     *
     * @param Chain $chain
     */
    public function setChain(Chain $chain)
    {
        $this->chain = $chain;
    }

    /**
     * Get chain
     *
     * @return Chain
     */
    public function getChain()
    {

        return $this->chain;
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
