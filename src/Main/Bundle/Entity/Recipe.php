<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Main\Bundle\Entity\Publication;

/**
 * Recipe
 *
 * @ORM\Entity
 */
class Recipe extends Publication
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getType()
    {

        return self::TYPE_RECIPE;
    }
}
