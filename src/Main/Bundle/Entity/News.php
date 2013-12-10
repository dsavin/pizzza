<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Main\Bundle\Entity\Publication;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * News
 *
 * @ORM\Entity
 * @Vich\Uploadable
 */
class News extends Publication
{

    /**
     * @var string
     *
     * @ORM\Column(name="post_type", type="integer", options={"default" = 0 })
     */
    private $post_type;

    const POST_TYPE_NEWS = 0;
    const POST_TYPE_INTERESTING = 1;

    public function __construct()
    {
        $this->setPostType(self::POST_TYPE_NEWS);

        parent::__construct();
    }

    public function getType()
    {

        return self::TYPE_NEWS;
    }

    public function setPostType($type)
    {
        $this->post_type = $type;

        return $this;
    }

    public function getPostType()
    {
        return $this->post_type;
    }
}
