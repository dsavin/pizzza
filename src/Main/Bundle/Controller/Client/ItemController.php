<?php

    namespace Main\Bundle\Controller\Client;

    use Main\Bundle\Controller\BaseController as Controller;

    use Symfony\Component\HttpFoundation\Request;

    use Symfony\Component\Routing\Route;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

    use Main\Bundle\Entity\Item;
    use Main\Bundle\Entity\Chain;

    /**
     * Branch controller.
     *
     */
    class ItemController extends Controller
    {

        /**
         * Displays single item branch
         *
         * @Template()
         */
        public function showAction($_locale, $_city, $chain_url, $item_url, Request $request)
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

            $entity = $em->getRepository('MainBundle:Item')->findOneBy(array(
                                                                              'url' => $item_url,
                                                                              'chain' => is_null($entityChain->getParent())?$entityChain->getId():$entityChain->getParent()->getId()
                                                                         ));
            if (!$entity) {
                throw $this->createNotFoundException('Нету такой пиццы');
            }

            $entities = $em->getRepository('MainBundle:Item')->findOthersItems(array(
                                                                                'id' => $entity->getId(),
                                                                                'chain' => is_null($entityChain->getParent())?$entityChain->getId():$entityChain->getParent()->getId(),
                                                                                'limit' => 3
                                                                            ));

            return array(
                'entity'      => $entity,
                'entityChain'   => $entityChain,
                'entities'      => $entities
            );
        }

        /**
         * Displays single item branch
         *
         * @Template()
         */
        public function allAction($_city, $chain_url, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $city = $this->getCityByUrl($_city);

            $entities = $em->getRepository('MainBundle:Branch')->getListBranches($chain_url, $city->getId(), $request->getLocale() );

            /* Розделить на выборку сети и потом выборку всех заведений
             *
             * if (!$entities) {
                throw $this->createNotFoundException('Нету такого заведения');
            }*/

            return array(
                'entities'      => $entities,
                'entityChain'   => $entities[0]->getChain(),
            );
        }
    }
