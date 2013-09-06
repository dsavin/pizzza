<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController as Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Item;
use Main\Bundle\Form\ItemType;
use Main\Bundle\Entity\Chain;
/**
 * Item controller.
 *
 * @Route("/admin")
 */
class ItemController extends Controller
{
    /**
     * Lists all Item entities.
     *
     * @Route("/{chain_id}/item/", name="admin_item", defaults={"_city" = "kiev"})
     * @Route("/{_city}/{chain_id}/item/", name="admin_item_city")
     * @Template()
     */
    public function indexAction($chain_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $entities = $em->getRepository('MainBundle:Item')->findBy(array('chain' => $entity));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Item entity.
     *
     * @Route("/{chain_id}/item/new", name="admin_item_new", defaults={"_city" = "kiev"})
     * @Route("/{_city}/{chain_id}/item/new", name="admin_item_new_city")
     * @Template()
     */
    public function newAction($chain_id, Request $request)
    {
        $entity = new Item();
        $form   = $this->createForm(new ItemType(), $entity);

        if ($request->isMethod("POST")) {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity->setChain($em->getRepository('MainBundle:Chain')->find($chain_id));

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_item', array('chain_id' => $chain_id)));
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/{id}/edit", name="admin_item_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $editForm = $this->createForm(new ItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Item entity.
     *
     * @Route("/{id}/update", name="admin_item_update")
     * @Method("POST")
     * @Template("MainBundle:Item:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_item_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Item entity.
     *
     * @Route("/{id}/delete", name="admin_item_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Item')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_item'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
