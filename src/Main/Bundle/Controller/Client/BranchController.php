<?php

    namespace Main\Bundle\Controller\Client;

    use Main\Bundle\Controller\BaseController as Controller;

    use Symfony\Component\HttpFoundation\Request;

    use Symfony\Component\Routing\Route;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

    use Main\Bundle\Entity\Branch;
    use Main\Bundle\Entity\Chain;
    use Main\Bundle\Entity\Photo;

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

            $branches = $em->getRepository('MainBundle:Branch')
                ->findBy(array(
                              'chain' => $entityChain->getId(),
                              'lang' => $request->getLocale(),
                              'status' => Branch::STATUS_ACTIVE
                         ));
            $entityChain->setBranchs($branches);

            $entity = $em->getRepository('MainBundle:Branch')->findOneBy(array(
                                                                              'url' => $branch_url,
                                                                              'chain' => is_null($entityChain->getParent())?$entityChain->getId():$entityChain->getParent()->getId(),
                                                                              'lang'    => $request->getLocale()
                                                                         ));
            if (!$entity) {
                throw $this->createNotFoundException('Нету такого заведения');
            }

            $entitiesPhoto = $entity->getPhotos();

            /** @var $commentRepository  */
            $commentRepository = $em->getRepository('MainBundle:CommentBranch');
            $comments = $commentRepository->findBy( array( 'chain' => $entityChain->getId(), 'branch' => $entity->getId() ), array('created_at'=>'DESC') );

            return array(
                'entity'      => $entity,
                'entityChain'   => $entityChain,
                'entitiesPhoto' => $entitiesPhoto,
                'comments' => $comments
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

            $entities = $em->getRepository('MainBundle:Branch')
                ->getListBranches($chain_url, $city->getId(), $request->getLocale() );

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
