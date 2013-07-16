<?php

namespace Main\Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Main\Bundle\Entity\Publication;
use Main\Bundle\Entity\RecipeIngredients;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Recipe
 *
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Recipe extends Publication
{

    /**
     * @var string
     *
     * @ORM\Column(name="big_text", type="text", nullable=true)
     */
    private $big_text;

    /**
     * @ORM\OneToMany(targetEntity="RecipeIngredients", mappedBy="recipe")
     **/
    private $ingredients;

    public function __construct()
    {
        parent::__construct();
        $this->ingredients = new ArrayCollection();
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

    /**
     * Add ingredient
     *
     * @param RecipeIngredients $ingredient
     * @return $this
     */
    public function addIngredient(RecipeIngredients $ingredient)
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }

    /**
     * Get ingredients
     *
     * @return Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

}
