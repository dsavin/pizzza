<?php

namespace Main\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
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

    public function __construct()
    {
        parent::__construct();

        $this->facebook_id = 0;
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
}