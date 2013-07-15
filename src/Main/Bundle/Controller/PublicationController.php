<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController as Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Publication;
use Main\Bundle\Entity\News;
use Main\Bundle\Entity\Recipe;
use Main\Bundle\Form\PublicationType;
use Main\Bundle\Form\RecipeType;
use Main\Bundle\Entity\RecipeIngredients;
use Main\Bundle\Entity\Ingredient;

use Symfony\Component\HttpFoundation\Tests\RequestContentProxy;

/**
 * Publication controller.
 *
 * @Route("/admin")
 */
class PublicationController extends Controller
{
    /**
     * Lists all Publication entities.
     *
     * @Route("/publication", name="admin_publication", defaults={"_city" = "kiev"})
     * @Route("/{_city}/publication", name="admin_publication_city")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:News')->findBy(array(),array('id'=>'DESC'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Publication entity.
     *
     * @Route("/publication/new/{id}", name="admin_publication_new", defaults={"_city" = "kiev", "id"=0})
     * @Route("/{_city}/publication/new/{id}", name="admin_publication_new_city", defaults={"id"=0})
     * @Template()
     */
    public function newAction(Request $request, $_city, $id)
    {
        $city = $this->getCurrentCity();
        $em = $this->getDoctrine()->getManager();
        $entity = new News();
        if ($id) {
            $entity = $em->getRepository('MainBundle:News')->find($id);
        }
        $form   = $this->createForm(new PublicationType(), $entity);

        if ($request->isMethod("POST")) {
            $form->bind($request);

            if ($form->isValid()) {
                $entity->setLang($request->getLocale());
                $entity->setCityId($city->getId());
                $em->persist($entity);

                $em->flush();

                return $this->redirect($this->generateUrlCity('admin_publication', array('_city' => $_city, '_locale' => $request->getLocale())));
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id' => $id
        );
    }


    //RECIPE ---------------------//



    /**
     * Lists all Recipe entities.
     *
     * @Route("/recipe", name="admin_recipe", defaults={"_city" = "kiev"})
     * @Route("/{_city}/recipe", name="admin_recipe_city")
     * @Template("MainBundle:Publication\Recipe:index.html.twig")
     */
    public function indexRecipeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Recipe')->findBy(array(),array('id'=>'DESC'));

        return array(
            'entities' => $entities,
        );
    }



    /**
     * Displays a form to create a new Recipe entity.
     *
     * @Route("/recipe/new/{id}", name="admin_recipe_new", defaults={"_city" = "kiev", "id"=0})
     * @Route("/{_city}/recipe/new/{id}", name="admin_recipe_new_city", defaults={"id"=0})
     * @Template("MainBundle:Publication\Recipe:new.html.twig")
     */
    public function newRecipeAction(Request $request, $_city, $id)
    {
        $city = $this->getCurrentCity();
        $em = $this->getDoctrine()->getManager();
        $entity = new Recipe();
        if ($id) {
            $entity = $em->getRepository('MainBundle:Recipe')->find($id);
        }
        $form   = $this->createForm(new RecipeType(), $entity);

        if ($request->isMethod("POST")) {
            $form->bind($request);

            if ($form->isValid()) {
                $entity->setLang($request->getLocale());
                $entity->setCityId($city->getId());
                $em->persist($entity);

                $em->flush();

                return $this->redirect($this->generateUrlCity('admin_recipe', array('_city' => $_city, '_locale' => $request->getLocale())));
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id' => $id
        );
    }

    /**
     *
     *
     * @Route("/recipe/ingredients/{id}", name="admin_recipe_ingredients", defaults={"_city" = "kiev", "id"=0})
     * @Route("/{_city}/recipe/ingredients/{id}", name="admin_recipe_ingredients_city", defaults={"id"=0})
     * @Template("MainBundle:Publication\Recipe:ingredients.html.twig")
     */
    public function editRecipeIngredientsAction(Request $request, $_city, $id)
    {
        $city = $this->getCurrentCity();
        $em = $this->getDoctrine()->getManager();
        $entityRecipe = $em->getRepository('MainBundle:Recipe')->find($id);
        $entitiesIngridient = $em->getRepository('MainBundle:Ingredient')->getAllWithIndex();
        $entitiesRecipeIngredients = $em->getRepository('MainBundle:RecipeIngredients')->findBy(array('recipe'=>$id));

        if ($request->isMethod("POST")) {
            $counts = $request->request->get('count');

            foreach ($entitiesRecipeIngredients as $entityRI) {
//                if (isset($counts[$entityRI->getIngredient()->getId()]) && !empty($counts[$entityRI->getIngredient()->getId()])) {
//                    $entityRI->getIngredient()->getId();
//
//                }
                $em->remove($entityRI);
            }

            $em->flush();

            foreach ($counts as $k=>$val) {
                if (empty($val)) continue;
                $entity = new RecipeIngredients($entityRecipe, $entitiesIngridient[$k]);
                $entity->setLang($request->getLocale());
                $entity->setCount($val);
                $em->persist($entity);
            }

            $em->flush();

            return $this->redirect($this->generateUrlCity('admin_recipe', array('_city' => $_city, '_locale' => $request->getLocale())));
        }

        return array(
            'entityRecipe' => $entityRecipe,
            'entitiesIngridient'   => $entitiesIngridient,
            'entitiesRecipeIngredients' => $entitiesRecipeIngredients,
            'id' => $id
        );
    }
}
