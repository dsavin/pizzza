<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;


use Doctrine\Common\Collections\ArrayCollection;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="Main\Bundle\Entity\CityRepository")
 */
class City
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=255)
     */
    private $name_en;


    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="children",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;


    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return City
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
     * Set url
     *
     * @param string $url
     * @return City
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set name_en
     *
     * @param string $name_en
     * @return City
     */
    public function setNameEn($name_en)
    {
        $this->name_en = $name_en;
    
        return $this;
    }

    /**
     * Get name_en
     *
     * @return string 
     */
    public function getNameEn()
    {
        return $this->name_en;
    }

    /**
     * Add children
     *
     * @param City $children
     */
    public function addMessage(City $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param City $parent
     */
    public function setParent(City $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return City
     */
    public function getParent()
    {

        return $this->parent;
    }

    public function __toString()
    {

        return __CLASS__;
    }
}
