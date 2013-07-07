<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient")
 * @ORM\Entity(repositoryClass="Main\Bundle\Entity\IngredientRepository")
 * @Vich\Uploadable
 */
class Ingredient
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=5)
     */
    private $lang;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

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
     * @return Ingredient
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
     * Set lang
     *
     * @param string $lang
     * @return Ingredient
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    
        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Ingredient
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
