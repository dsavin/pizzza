<?php

namespace Main\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *  @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="usernameCanonical", column=@ORM\Column(type="string", name="username_canonical", length=255, nullable=true, unique=false)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="facebook_id", type="integer", options={"default" = 0, "comment" = "facebook id"})
     */
    protected $facebook_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="google_id", type="integer", options={"default" = 0, "comment" = "google_id"})
     */
    protected $google_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="vk_id", type="integer", options={"default" = 0, "comment" = "vk_id"})
     */
    protected $vk_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="twitter_id", type="integer", options={"default" = 0, "comment" = "twitter_id"})
     */
    protected $twitter_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image_user", type="string", length=400, nullable=true)
     */
    protected $imageUser;

    public function __construct()
    {
        parent::__construct();

        $this->facebook_id = 0;
        $this->vk_id = 0;
        $this->google_id = 0;
        $this->twitter_id = 0;
    }

    /**
     * Set facebook_id
     *
     * @param integer $id
     * @return User
     */
    public function setFacebookId($id)
    {
        $this->facebook_id = $id;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return integer
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set google_id
     *
     * @param integer $googleId
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;
    
        return $this;
    }

    /**
     * Get google_id
     *
     * @return integer 
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set vk_id
     *
     * @param integer $vkId
     * @return User
     */
    public function setVkId($vkId)
    {
        $this->vk_id = $vkId;
    
        return $this;
    }

    /**
     * Get vk_id
     *
     * @return integer 
     */
    public function getVkId()
    {
        return $this->vk_id;
    }

    /**
     * Set twitter_id
     *
     * @param integer $twitterId
     * @return User
     */
    public function setTwitterId($twitterId)
    {
        $this->twitter_id = $twitterId;
    
        return $this;
    }

    /**
     * Get twitter_id
     *
     * @return integer 
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
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
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set imageUser
     *
     * @param string $imageUser
     * @return User
     */
    public function setImageUser($imageUser)
    {
        $this->imageUser = $imageUser;
    
        return $this;
    }

    /**
     * Get imageUser
     *
     * @return string 
     */
    public function getImageUser()
    {
        return $this->imageUser;
    }
}