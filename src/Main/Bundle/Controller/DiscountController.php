<?php

    namespace Main\Bundle\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Main\Bundle\Controller\BaseController as Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

    use Main\Bundle\Entity\Discount;
    use Main\Bundle\Entity\Chain;

    use Main\Bundle\Form\DiscountType;

    /**
     * Discount controller.
     *
     */
    class DiscountController extends Controller
    {
        /**
         * Lists all Discount entities.
         *
         * @Route("/admin/discount/{chain_id}/", name="admin_discount", defaults={"_city" = "kiev"})
         * @Route("/admin/{_city}/discount/{chain_id}/", name="admin_discount_city")
         * @Template()
         */
        public function indexAction($_city, $chain_id, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $entityCity = $this->getCityByUrl($_city);

            $entities = $em->getRepository('MainBundle:Discount')->findBy(array(
                                                                               'city_id' => $entityCity->getId(),
                                                                               'parent' => null,
                                                                               'chain' => $chain_id
                                                                          ));

            return array(
                'entities' => $entities,
            );
        }

        /**
         * Displays a form to create a new Discount entity.
         *
         * @Route("/admin/discount/{chain_id}/new/{dis_id}/", name="admin_discount_new", defaults={"_city" = "kiev", "dis_id"="0"})
         * @Route("/admin/{_city}/discount/{chain_id}/new/{dis_id}/", name="admin_discount_new_city", defaults={"dis_id"="0"})
         * @Template()
         */
        public function newAction($_city, $chain_id, $dis_id, Request $request)
        {
            $entity = new Discount();
            $em = $this->getDoctrine()->getManager();

            if ($dis_id) {
                $entityParent = $em->getRepository('MainBundle:Discount')->find($dis_id);
                $entity = clone $entityParent;
            }

            $form = $this->createForm(new DiscountType(), $entity);

            if ($request->isMethod("POST")) {
                $form->bind($request);

                if ($form->isValid()) {

                    $entityChain = $em->getRepository('MainBundle:Chain')->find($chain_id);
                    $entityCity = $this->getCityByUrl($_city);

                    $entity->setChain($entityChain);
                    $entity->setCityId($entityCity->getId());
                    $entity->setLang($request->getLocale());
                    if ($dis_id) {
                        $entity->setParent($entityParent);
                    }

                    $em->persist($entity);
                    $em->flush();

                    return $this->redirect($this->generateUrlCity('admin_discount', array('chain_id' => $chain_id, '_city' => $_city)));
                }
            }

            return array(
                'entity' => $entity,
                'form'   => $form->createView(),
                'dis_id' => $dis_id
            );
        }

        /**
         * Displays a form to edit an existing Discount entity.
         *
         * @Route("/admin/discount/{chain_id}/{id}/edit/", name="admin_discount_edit", defaults={"_city" = "kiev"})
         * @Route("/admin/{_city}/discount/{chain_id}/{id}/edit/", name="admin_discount_edit_city")
         * @Template()
         */
        public function editAction($id, $_city, $chain_id, Request $request)
        {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('MainBundle:Discount')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Discount entity.');
            }

            $editForm = $this->createForm(new DiscountType(), $entity);
            $deleteForm = $this->createDeleteForm($id);

            if ($request->isMethod("POST")) {
                $editForm->bind($request);

                if ($editForm->isValid()) {
                    $em->persist($entity);
                    $em->flush();

                    return $this->redirect($this->generateUrlCity('admin_discount', array('chain_id' => $chain_id, '_city' => $_city)));
                }
            }

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }

        /**
         * Deletes a Discount entity.
         *
         * @Route("/{id}/delete", name="admin_discount_delete")
         * @Method("POST")
         */
        public function deleteAction(Request $request, $id)
        {
            $form = $this->createDeleteForm($id);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('MainBundle:Discount')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Discount entity.');
                }

                $em->remove($entity);
                $em->flush();
            }

            return $this->redirect($this->generateUrlCity('admin_discount'));
        }

        private function createDeleteForm($id)
        {
            return $this->createFormBuilder(array('id' => $id))
                ->add('id', 'hidden')
                ->getForm();
        }
    }
