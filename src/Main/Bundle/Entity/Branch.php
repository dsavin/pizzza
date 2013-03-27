<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Main\Bundle\Entity\Photo;
use Main\Bundle\Entity\Feature;

/**
 * Branch
 *
 * @ORM\Table(name="branch")
 * @ORM\Entity(repositoryClass="Main\Bundle\Entity\BranchRepository")
 */
class Branch
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
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var array
     *
     * @ORM\Column(name="phones", type="json_array")
     */
    private $phones;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="decimal")
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="decimal")
     */
    private $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="work_at", type="text")
     */
    private $work_at;

    /**
     * @var string
     *
     * @ORM\Column(name="social_text", type="string", length=255)
     */
    private $social_text;

    /**
     * @var string
     *
     * @ORM\Column(name="metro", type="string", length=255)
     */
    private $metro;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=5)
     */
    private $lang;

    /**
    * @ORM\ManyToOne(targetEntity="Chain")
    * @ORM\JoinColumn(name="chain_id", referencedColumnName="id")
    */
    private $chain;

    /**
     * @ORM\OneToMany(targetEntity="Branch", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="children",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     * @var datetime $updated_at
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="branch")
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="Feature", inversedBy="branches")
     * @ORM\JoinTable(name="feature_branch",
     *      joinColumns={@ORM\JoinColumn(name="feature_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="branch_id", referencedColumnName="id")}
     *      )
     */
    private $features;

    public function __construct($lang = 'ru')
    {
        $this->children = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->features = new ArrayCollection();
        $this->updated_at = new \DateTime();
        $this->keywords = '';
        $this->lang = $lang;
        $this->id = 0;
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
     * Set street
     *
     * @param string $street
     * @return Branch
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Branch
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Branch
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
     * @return Branch
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
     * @return Branch
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
     * Set url
     *
     * @param string $url
     * @return Branch
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
     * Set phones
     *
     * @param array $phones
     * @return Branch
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;
    
        return $this;
    }

    /**
     * Get phones
     *
     * @return array 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Branch
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
     * Set lat
     *
     * @param float $lat
     * @return Branch
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Branch
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    
        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set work_at
     *
     * @param string $workAt
     * @return Branch
     */
    public function setWorkAt($workAt)
    {
        $this->work_at = $workAt;
    
        return $this;
    }

    /**
     * Get work_at
     *
     * @return string 
     */
    public function getWorkAt()
    {
        return $this->work_at;
    }

    /**
     * Set social_text
     *
     * @param string $socialText
     * @return Branch
     */
    public function setSocialText($socialText)
    {
        $this->social_text = $socialText;
    
        return $this;
    }

    /**
     * Get social_text
     *
     * @return string 
     */
    public function getSocialText()
    {
        return $this->social_text;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return Branch
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
     * Set chain
     *
     * @param string $chain
     * @return Branch
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

    /**
     * Set updated_at
     *
     * @param datetime $updated_at
     * @return Branch
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
     * Add children
     *
     * @param Branch $children
     */
    public function addChildren(Branch $children)
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
     * @param Branch $parent
     */
    public function setParent(Branch $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Branch
     */
    public function getParent()
    {

        return $this->parent;
    }

    public function getStarsByMaxRating($max_rating)
    {
        $stars = 5*($this->rating/$max_rating*100)/100;

        return (int)$stars;
    }

    /**
     * Add photo
     *
     * @param Photo $photo
     */
    public function addPhoto(Photo $photo)
    {
        $this->photos[] = $photo;
    }

    /**
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param ArrayCollection $features
     * @return Branch
     */
    public function setFeatures(ArrayCollection $features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Add feature
     *
     * @param Feature $feature
     */
    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;
    }

    /**
     * Get features
     *
     * @return Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set metro
     *
     * @param string $metro
     * @return Branch
     */
    public function setMetro($metro)
    {
        $this->metro = trim($metro);

        return $this;
    }

    /**
     * Get metro
     *
     * @return string
     */
    public function getMetro()
    {
        return trim($this->metro);
    }
}
