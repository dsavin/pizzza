<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\TypePublication;
use Main\Bundle\Form\TypePublicationType;

/**
 * TypePublication controller.
 *
 * @Route("/admin/type_publication")
 */
class TypePublicationController extends Controller
{
    /**
     * Lists all TypePublication entities.
     *
     * @Route("/", name="admin_type_publication")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:TypePublication')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a TypePublication entity.
     *
     * @Route("/{id}/show", name="admin_type_publication_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:TypePublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypePublication entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new TypePublication entity.
     *
     * @Route("/new", name="admin_type_publication_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TypePublication();
        $form   = $this->createForm(new TypePublicationType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new TypePublication entity.
     *
     * @Route("/create", name="admin_type_publication_create")
     * @Method("POST")
     * @Template("MainBundle:TypePublication:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new TypePublication();
        $form = $this->createForm(new TypePublicationType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_type_publication_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TypePublication entity.
     *
     * @Route("/{id}/edit", name="admin_type_publication_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:TypePublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypePublication entity.');
        }

        $editForm = $this->createForm(new TypePublicationType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TypePublication entity.
     *
     * @Route("/{id}/update", name="admin_type_publication_update")
     * @Method("POST")
     * @Template("MainBundle:TypePublication:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:TypePublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypePublication entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TypePublicationType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_type_publication_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TypePublication entity.
     *
     * @Route("/{id}/delete", name="admin_type_publication_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:TypePublication')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TypePublication entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_type_publication'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
