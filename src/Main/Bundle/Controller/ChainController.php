<?php

namespace Main\Bundle\Controller;

use Main\Bundle\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Chain;
use Main\Bundle\Form\ChainType;

/**
 * Chain controller.
 *
 */
class ChainController extends Controller
{

    /**
     * Lists all Chain entities.
     *
     * @Route("/admin/chain/", name="admin_chain", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/chain/", name="admin_chain_city")
     * @Template()
     */
    public function indexAction($_city, Request $request)
    {

        $this->checkCity($_city);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Chain')->findBY(array('city_id' => $this->getCityByUrl($_city)->getId(), 'parent' => null));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Chain entity.
     *
     * @Route("/admin/chain/new/{id}", name="admin_chain_new", defaults={"_city" = "kiev", "id"="0"})
     * @Route("/admin/{_city}/chain/new/{id}", name="admin_chain_new_city", defaults={"id"="0"})
     * @Template()
     */
    public function newAction($_city, $id = 0)
    {
        $this->checkCity($_city);
        $entity = new Chain();
        if ($id != 0) {
            $em = $this->getDoctrine()->getManager();
            $entityParent = $em->getRepository('MainBundle:Chain')->find($id);
            $entity->setUrl($entityParent->getUrl());
            $entity->setSite($entityParent->getSite());
            $entity->setType($entityParent->getType());
        }

        $form = $this->createForm(new ChainType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id
        );
    }

    /**
     * Creates a new Chain entity.
     *
     * @Route("/admin/chain/create/{id}", name="admin_chain_create", defaults={"_city" = "kiev", "id"="0"})
     * @Route("/admin/{_city}/chain/create/{id}", name="admin_chain_create_city", defaults={"id"="0"})
     * @Method("POST")
     * @Template("MainBundle:Chain:new.html.twig")
     */
    public function createAction($_city, $id = 0, Request $request)
    {
        $this->checkCity($_city);
        $entity = new Chain();
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $entityParent = $em->getRepository('MainBundle:Chain')->find($id);
            $entity->setUrl($entityParent->getUrl());
            $entity->setSite($entityParent->getSite());
            $entity->setType($entityParent->getType());
        }

        $form = $this->createForm(new ChainType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setCityId($this->getCityByUrl($_city)->getId());
            $entity->setLang($request->getLocale());
            if ($id != 0) {
                $entity->setParent($entityParent);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrlCity('admin_chain', array('_locale' => $request->getLocale())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id
        );
    }

    /**
     * Displays a form to edit an existing Chain entity.
     *
     * @Route("/admin/chain/{id}/edit", name="admin_chain_edit", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/chain/{id}/edit", name="admin_chain_edit_city")
     * @Template()
     */
    public function editAction($id, $_city, Request $request)
    {

        $this->checkCity($_city);
        $em = $this->getDoctrine()->getManager();

        if ($request->getLocale() != 'ru') {
            $entity = $em->getRepository('MainBundle:Chain')->findOneBy(array('id' => $id, 'lang' => $request->getLocale()));
        } else {
            $entity = $em->getRepository('MainBundle:Chain')->find($id);
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $editForm = $this->createForm(new ChainType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Chain entity.
     *
     * @Route("/admin/chain/{id}/update", name="admin_chain_update", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/chain/{id}/update", name="admin_chain_update_city")
     * @Method("POST")
     * @Template("MainBundle:Chain:edit.html.twig")
     */
    public function updateAction($id, $_city, Request $request)
    {
        $this->checkCity($_city);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Chain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChainType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrlCity('admin_chain_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Chain entity.
     *
     * @Route("/admin/chain/{id}/delete", name="admin_chain_delete", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/chain/{id}/update", name="admin_chain_delete_city")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, $_city)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Chain')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chain entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrlCity('admin_chain', array('_locale' => 'ru')));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                ->add('id', 'hidden')
                ->getForm()
        ;
    }

}
