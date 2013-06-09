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

    public function __construct()
    {
        parent::__construct();
    }

    public function getType()
    {

        return self::TYPE_NEWS;
    }
}
