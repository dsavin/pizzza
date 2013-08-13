<?php

    namespace Main\Bundle\Controller\Client;

    use Main\Bundle\Controller\BaseController as Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

    use Main\Bundle\Entity\Discount;
    use Main\Bundle\Entity\Chain;


    /**
     * Discount controller.
     *
     */
    class DiscountController extends Controller
    {

        /**
         * Displays a Discount entity.
         *
         * @Template()
         */
        public function showAction($_city, $chain_url, $dis_url, Request $request)
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

            $entityDiscount = $em->getRepository('MainBundle:Discount')->findOneBy(array(
                                                                                        'chain'   => $entityChain->getId(),
                                                                                        'city_id' => $city->getId(),
                                                                                        'lang'    => $request->getLocale(),
                                                                                        'url'     => $dis_url
                                                                                   ));

            if (!$entityDiscount) {
                throw $this->createNotFoundException('Нету такой акции');
            }

            $entitiesDiscounts = $em->getRepository('MainBundle:Discount')->findSameDiscount(array(
                                                                                                  'chain'   => $entityChain->getId(),
                                                                                                  'city_id' => $city->getId(),
                                                                                                  'lang'    => $request->getLocale(),
                                                                                                  'not_in_id'     => $entityDiscount->getId()
                                                                                             ));

            $entitiesOthers = $em->getRepository('MainBundle:Discount')->findOthers(array(
                                                                                        'chain'   => $entityChain->getId(),
                                                                                        'city_id' => $city->getId(),
                                                                                        'lang'    => $request->getLocale(),
                                                                                        'not_in_id'     => $entityDiscount->getId()
                                                                                    ));

            return array(
                'entityChain'    => $entityChain,
                'entityDiscount' => $entityDiscount,
                'entitiesDiscounts' => $entitiesDiscounts,
                'entitiesOthers' => $entitiesOthers
            );
        }

        /**
         * Displays a form to create a new Discount entity.
         *
         * @Template()
         */
        public function listAction($_city, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $city = $this->getCityByUrl($_city);
            $paginator  = $this->get('knp_paginator');

            $queryDiscouts = $em->getRepository('MainBundle:Discount')->getAllWithChainByCity($city->getId(),
                                                                                            $request->getLocale());

            $pagination = $paginator->paginate(
                $queryDiscouts,
                $this->get('request')->query->get('page', 1)/*page number*/,
                20/*limit per page*/
            );
            $pagination->setTemplate('MainBundle:PageLayout:sliding.html.twig');

            $entity = $em->getRepository('MainBundle:Page')->findOneBy(array(
                                                                            'name'    => 'discounts',
                                                                            'city_id' => $city->getId(),
                                                                            'lang'    => $request->getLocale()
                                                                       ));

            return array(
                'pagination'    => $pagination,
                'entity' => $entity
            );
        }
    }
