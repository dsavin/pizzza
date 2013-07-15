<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Publication;
use Main\Bundle\Entity\News;
use Main\Bundle\Entity\Recipe;
use Main\Bundle\Entity\Ingredient;
use Main\Bundle\Entity\RecipeIngredients;


/**
 * Publication controller.
 *
 */
class PublicationController extends Controller
{

    /**
     * Displays a News entity.
     *
     * @Template()
     */
    public function newsAction($_city, $url, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $entity = $em->getRepository('MainBundle:News')->findOneBy(array(
                                                                              'url'     => $url,
                                                                              'city_id' => $city->getId(),
                                                                              'lang'    => $request->getLocale()
                                                                         ));
        if (!$entity) {
            throw $this->createNotFoundException('Нету такой новости');
        }

        $entities = $em->getRepository('MainBundle:Publication')->findMoreEntities(array(
                                                                                    'id'     => $entity->getId(),
                                                                                    'city_id' => $city->getId(),
                                                                                    'lang'    => $request->getLocale()
                                                                                ));

        return array(
            'entity'       => $entity,
            'entities' => $entities
        );
    }

    /**
     * Publication
     *
     * @Template()
     */
    public function newsListAction($_city, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $pagination = $em->getRepository('MainBundle:News')->findBy(array('city_id' => $city->getId(), 'lang' => $request->getLocale()), array('id'=>'DESC'));

        return array(
            'pagination' => $pagination
        );
    }

    /**
     * Publication
     *
     * @Template()
     */
    public function recipesListAction($_city, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $pagination = $em->getRepository('MainBundle:Recipe')->findBy(array('city_id' => $city->getId(), 'lang' => $request->getLocale()), array('id'=>'DESC'));

        return array(
            'pagination' => $pagination
        );
    }

    /**
     * Displays a recipe entity.
     *
     * @Template()
     */
    public function recipeAction($_city, $url, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $entity = $em->getRepository('MainBundle:Recipe')->findOneBy(array(
            'url'     => $url,
            'city_id' => $city->getId(),
            'lang'    => $request->getLocale()
        ));
        if (!$entity) {
            throw $this->createNotFoundException('Нету такой новости');
        }

        $entities = $em->getRepository('MainBundle:Publication')->findMoreRecipeEntities(array(
            'id'     => $entity->getId(),
            'city_id' => $city->getId(),
            'lang'    => $request->getLocale(),
        ));

        return array(
            'entity'       => $entity,
            'entities' => $entities
        );
    }
}