<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Ingredient;
use Main\Bundle\Form\IngredientType;

/**
 * Ingredient controller.
 *
 * @Route("/admin/ingredient")
 */
class IngredientController extends Controller
{
    /**
     * Lists all Ingredient entities.
     *
     * @Route("/", name="admin_ingredient")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Ingredient')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Ingredient entity.
     *
     * @Route("/new", name="admin_ingredient_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ingredient();
        $form   = $this->createForm(new IngredientType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Ingredient entity.
     *
     * @Route("/create", name="admin_ingredient_create")
     * @Method("POST")
     * @Template("MainBundle:Ingredient:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Ingredient();
        $form = $this->createForm(new IngredientType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setLang($request->getLocale());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ingredient_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Ingredient entity.
     *
     * @Route("/{id}/edit", name="admin_ingredient_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Ingredient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingredient entity.');
        }

        $editForm = $this->createForm(new IngredientType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Ingredient entity.
     *
     * @Route("/{id}/update", name="admin_ingredient_update")
     * @Method("POST")
     * @Template("MainBundle:Ingredient:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Ingredient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingredient entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new IngredientType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ingredient_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ingredient entity.
     *
     * @Route("/{id}/delete", name="admin_ingredient_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Ingredient')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ingredient entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ingredient'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
