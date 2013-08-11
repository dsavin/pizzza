<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Page controller.
 *
 */
class PageController extends Controller
{

    /**
     * Index page
     *
     * @Template()
     */
    public function indexAction($_city, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $entity = $em->getRepository('MainBundle:Page')->findOneBy(array(
                                                                        'name'    => 'index',
                                                                        'city_id' => $city->getId(),
                                                                        'lang'    => $request->getLocale()
                                                                   ));
        if (!$entity) {
            throw $this->createNotFoundException('Нету тут такой Страницы');
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays branches on map.
     *
     * @Template()
     */
    public function mapAction($_city, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $entity = $em->getRepository('MainBundle:Page')->findOneBy(array(
                                                                        'name'    => 'map',
                                                                        'city_id' => $city->getId(),
                                                                        'lang'    => $request->getLocale()
                                                                   ));
        if (!$entity) {
            throw $this->createNotFoundException('Нету тут такой Страницы');
        }

        $chains = $em->getRepository('MainBundle:Chain')->findBy(array(
                                                                        'city_id' => $city->getId(),
                                                                        'lang'    => $request->getLocale()
                                                                   ));

        return array(
            'entity' => $entity,
            'chains' => $chains
        );
    }
}
