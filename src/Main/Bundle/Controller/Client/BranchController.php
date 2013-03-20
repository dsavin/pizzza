<?php

    namespace Main\Bundle\Controller\Client;

    use Main\Bundle\Controller\BaseController as Controller;

    use Symfony\Component\HttpFoundation\Request;

    use Symfony\Component\Routing\Route;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

    use Main\Bundle\Entity\Branch;
    use Main\Bundle\Entity\Chain;

    /**
     * Branch controller.
     *
     */
    class BranchController extends Controller
    {

        /**
         * Displays single item branch
         *
         * @Template()
         */
        public function showAction($_locale, $_city, $chain_url, $branch_url, Request $request)
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

            $entity = $em->getRepository('MainBundle:Branch')->findOneBy(array(
                                                                              'url' => $branch_url,
                                                                              'chain' => $entityChain->getId(),
                                                                              'lang'    => $request->getLocale()
                                                                         ));
            if (!$entity) {
                throw $this->createNotFoundException('Нету такого заведения');
            }

            return array(
                'entity'      => $entity,
                'entityChain'   => $entityChain
            );
        }
    }
