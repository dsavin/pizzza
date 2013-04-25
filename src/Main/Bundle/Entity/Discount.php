<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Main\Bundle\Entity\Chain;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Discount
 *
 * @ORM\Table(name="discount")
 * @ORM\Entity(repositoryClass="Main\Bundle\Entity\DiscountRepository")
 * @Vich\Uploadable
 */
class Discount
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="smallint")
     */
    private $city_id;

    /**
     * @Vich\UploadableField(mapping="discount_img", fileNameProperty="imageName")
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

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     * @var datetime $updated_at
     */
    private $updated_at;

    /**
     * @var string
     *
     * @ORM\Column(name="short_text", type="text", nullable=true)
     */
    private $short_text;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=5)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="time_work", type="string", length=255, nullable=true)
     */
    private $time_work;

    /**
     * @ORM\OneToMany(targetEntity="Discount", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Discount", inversedBy="children",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;
    
    /**
    * @ORM\ManyToOne(targetEntity="Chain")
    * @ORM\JoinColumn(name="chain_id", referencedColumnName="id")
    */
    private $chain;
    
    
    public function __construct($lang = 'ru')
    {
        $this->children = new ArrayCollection();
        $this->updated_at = new \DateTime();
        $this->keywords = '';
        $this->lang = $lang;
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
     * Set url
     *
     * @param string $url
     * @return Discount
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
     * Set name
     *
     * @param string $name
     * @return Discount
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
     * Set title
     *
     * @param string $title
     * @return Discount
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Discount
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
     * Set keywords
     *
     * @param string $keywords
     * @return Discount
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set imageName
     *
     * @param string $url
     * @return Discount
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


    /**
     * Set updated_at
     *
     * @param datetime $updated_at
     * @return Discount
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Discount
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return Discount
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
     * Set time_work
     *
     * @param string $timeWork
     * @return Discount
     */
    public function setTimeWork($timeWork)
    {
        $this->time_work = $timeWork;
    
        return $this;
    }

    /**
     * Get time_work
     *
     * @return string 
     */
    public function getTimeWork()
    {
        return $this->time_work;
    }
    
    /**
     * Set type
     *
     * @param integer $type
     * @return Discount
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set city_id
     *
     * @param integer $city_id
     * @return Discount
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    /**
     * Get city_id
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->city_id;
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
     * @param Discount $parent
     */
    public function setParent(Discount $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Discount
     */
    public function getParent()
    {

        return $this->parent;
    }
    
    /**
     * Set chain
     *
     * @param string $chain
     * @return Discount
     */
    public function setChain( Chain $chain)
    {
        $this->chain = $chain;

        return $this;
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

    public function __clone()
    {
        $this->id = null;
    }


    /**
     * Set short_text
     *
     * @param string $short_text
     * @return Discount
     */
    public function setShortText($short_text)
    {
        $this->short_text = $short_text;

        return $this;
    }

    /**
     * Get short_text
     *
     * @return string
     */
    public function getShortText()
    {
        return $this->short_text;
    }

}
