<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Main\Bundle\Entity\ChainRepository;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Chain;
use Main\Bundle\Entity\Discount;
use Main\Bundle\Entity\Comment;

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
    public function showAction($chain_url, $_city, Request $request)
    {
        $city = $this->getCityByUrl($_city);
        $em = $this->getDoctrine()->getManager();
        /** @var $commentRepository  */
        $commentRepository = $em->getRepository('MainBundle:Comment');
        $entity = $em->getRepository('MainBundle:Chain')->findOneBy(array('url' => $chain_url, 'lang' => $request->getLocale(), 'city_id' => $city->getId()));


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $comments = $commentRepository->getLimmitComments(array('chain' => $entity->getId(), 'limit'=>2));

        return array(
            'entity'   => $entity,
            'comments' => $comments
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
            'entityChain'       => $entityChain,
            'entitiesDiscounts' => $entitiesDiscounts
        );
    }

    /**
     * Finds and displays a Chain entity.
     *
     * @Template()
     */
    public function deliveryAction($chain_url, $_city, Request $request)
    {
        $city = $this->getCityByUrl($_city);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Chain')->findOneBy(array('url' => $chain_url, 'lang' => $request->getLocale(), 'city_id' => $city->getId()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        /** @var $commentRepository  */
        $commentRepository = $em->getRepository('MainBundle:CommentDelivery');
        $comments = $commentRepository->findBy( array('chain' => $entity->getId()), array('created_at'=>'DESC') );

        return array(
            'entity' => $entity,
            'comments' => $comments
        );
    }


    /**
     * Finds and displays a Chain List with Branches.
     *
     * @Template()
     */
    public function allAction($_city, Request $request)
    {
        $city = $this->getCityByUrl($_city);
        $em = $this->getDoctrine()->getManager();

        /**
         * @var $chainRepo ChainRepository
         */
        $chainRepo = $em->getRepository('MainBundle:Chain');
        $entities = $chainRepo->getAllNotDelivery($city, $request->getLocale());

        if (!$entities) {
            throw $this->createNotFoundException('Нету доставок в данном городе');
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Страница всех доставок
     *
     * @Template()
     */
    public function deliveryListAction($_city, Request $request)
    {
        $city = $this->getCityByUrl($_city);
        $em = $this->getDoctrine()->getManager();

        /**
         * @var $chainRepo ChainRepository
         */
        $chainRepo = $em->getRepository('MainBundle:Chain');
        $entities = $chainRepo->getAllDelivery($city, $request->getLocale());

        if (!$entities) {
            throw $this->createNotFoundException('Нету доставок в данном городе');
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * All Comments
     *
     * @Template()
     */
    public function commentsAction($chain_url, $_city, Request $request)
    {
        $city = $this->getCityByUrl($_city);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Chain')->findOneBy(array('url' => $chain_url, 'lang' => $request->getLocale(), 'city_id' => $city->getId()));

        /** @var $commentRepository  */
        $commentRepository = $em->getRepository('MainBundle:Comment');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $comments = $commentRepository->getLimmitComments(array('chain' => $entity->getId()));

        return array(
            'entity' => $entity,
            'comments' => $comments
        );
    }
}
