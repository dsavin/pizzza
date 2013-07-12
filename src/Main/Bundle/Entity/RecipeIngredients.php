<?php

namespace Main\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Main\Bundle\Entity\Recipe;
use Main\Bundle\Entity\Ingredient;

/**
 * RecipeIngredients
 *
 * @ORM\Table(name="recipe_ingredients")
 * @ORM\Entity
 */
class RecipeIngredients
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="ingredients")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id", nullable=false)
     **/
    private $recipe;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="recipes")
     * @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id", nullable=false)
     **/
    private $ingredient;

    /**
     * @var string
     *
     * @ORM\Column(name="count", type="string", length=255, nullable=true)
     */
    private $count;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=5)
     */
    private $lang;

    public function __construct($recipe, $ingridient, $lang = 'ru')
    {
        $this->setRecipe($recipe);
        $this->setIngredient($ingridient);
        $this->setLang($lang);
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
     * Set count
     *
     * @param string $count
     * @return RecipeIngredients
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return string 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return RecipeIngredients
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
     * Set recipe
     *
     * @param Recipe $recipe
     * @return $this
     */
    public function setRecipe(Recipe $recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * Get recipe
     *
     * @return Recipe
     */
    public function getRecipe()
    {

        return $this->recipe;
    }

    /**
     * Set ingredient
     *
     * @param Ingredient $ingredient
     * @return $this
     */
    public function setIngredient(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get ingredient
     *
     * @return Ingredient
     */
    public function getIngredient()
    {

        return $this->ingredient;
    }
}
