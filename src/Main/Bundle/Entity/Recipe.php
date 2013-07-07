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

    /**
     * @var string
     *
     * @ORM\Column(name="big_text", type="text", nullable=true)
     */
    private $big_text;

    public function __construct()
    {
        parent::__construct();
    }

    public function getType()
    {

        return self::TYPE_RECIPE;
    }

    public function getBigText()
    {

        return $this->big_text;
    }

    /**
     * @param string $text
     * @return Recipe $this
     */
    public function setBigText($text)
    {
        $this->big_text = $text;

        return $this;
    }

}
