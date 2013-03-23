<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Chain;
use Main\Bundle\Entity\Discount;

/**
 * Chain controller.
 *
 */
class ChainController extends Controller
{
    /**
     * Finds and displays a Chain entity.
     *
     * @Template()
     */
    public function showAction($url, $_locale, $_city)
    {
        $this->checkCity($_city);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Chain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * Displays discounts list form one chain
     *
     * @Template()
     */
    public function discountsAction($_city, $chain_url, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);



        $entityChain = $em->getRepository('MainBundle:Chain')->findOneBy(array(
                                                                              'url'     => $chain_url,
                                                                              'city_id' => $city->getId(),
                                                                              'lang'    => $request->getLocale()
                                                                         ));
        if (!$entityChain) {
            throw $this->createNotFoundException('Нету тут такой сети');
        }



        $entitiesDiscounts = $em->getRepository('MainBundle:Discount')->findBy(array(
                                                                                          'chain'   => $entityChain->getId(),
                                                                                          'city_id' => $city->getId(),
                                                                                          'lang'    => $request->getLocale(),
                                                                                     ));

        return array(
            'entityChain' => $entityChain,
            'entitiesDiscounts' => $entitiesDiscounts
        );
    }
}
