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
         * Displays a form to create a new Discount entity.
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

            return array(
                'entityChain'    => $entityChain,
                'entityDiscount' => $entityDiscount,
                'entitiesDiscounts' => $entitiesDiscounts
            );
        }
    }
