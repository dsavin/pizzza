<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\Common\Collections\ArrayCollection;

use Main\Bundle\Entity\Branch;

/**
 * Feature
 *
 * @ORM\Table(name="feature")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Feature
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
     * @ORM\ManyToMany(targetEntity="Branch", mappedBy="features")
     */
    private $branches;

    /**
     * @var string
     *
     * @ORM\Column(name="alt_teg", type="string", length=255)
     */
    private $alt_teg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @Vich\UploadableField(mapping="feature", fileNameProperty="imageName")
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
        $this->branches = new ArrayCollection();
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
     * Set alt_teg
     *
     * @param string $altTeg
     * @return Feature
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
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Feature
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
     * Set status
     *
     * @param integer $status
     * @return Feature
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
     * Set branches
     *
     * @param ArrayCollection $branches
     */
    public function setBranches(ArrayCollection $branches)
    {
        $this->branches = $branches;
    }

    /**
     * Get branches
     *
     * @return ArrayCollection
     */
    public function getBranches()
    {

        return $this->branches;
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
