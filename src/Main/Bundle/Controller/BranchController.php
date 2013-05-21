<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Branch;
use Main\Bundle\Entity\Chain;
use Main\Bundle\Entity\Photo;

use Main\Bundle\Form\BranchType;

/**
 * Branch controller.
 *
 * @Route("/admin")
 */
class BranchController extends Controller
{

    /**
     * Lists all Branch entities.
     *
     * @Route("/{chain_id}/branch/", name="admin_branch", defaults={"_city" = "kiev"})
     * @Route("/{_city}/{chain_id}/branch/", name="admin_branch_city")
     * @Template()
     */
    public function indexAction($chain_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $entities = $em->getRepository('MainBundle:Branch')->findBy(array('chain' => $entity, 'lang' => 'ru'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Branch entity.
     *
     * @Route("/{chain_id}/branch/{branch_id}/new", name="admin_branch_new", defaults={"_city" = "kiev", "branch_id" = 0})
     * @Route("/{_city}/{chain_id}/branch/{branch_id}/new", name="admin_branch_new_city", defaults={"branch_id" = 0})
     * @Template()
     */
    public function newAction($chain_id, $branch_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Branch();
        if ($branch_id) {
            $parent = $em->getRepository('MainBundle:Branch')->find($branch_id);
            $entity->setUrl($parent->getUrl());
            $entity->setLat($parent->getlng());
            $entity->setLng($parent->getLng());
            $entity->setPhones($parent->getPhones());
            $entity->setRating($parent->getRating());
            $entity->setParent($parent);
        }
        $form = $this->createForm(new BranchType(), $entity);
        if ($request->isMethod("POST")) {
            $form->bind($request);

            if ($form->isValid()) {
                $entity->setLang($request->getLocale());
                $entity->setChain($em->getRepository('MainBundle:Chain')->find($chain_id));
                
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrlCity('admin_branch', array('chain_id' => $chain_id)));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'branch_id' => $branch_id,
            'chain_id' => $chain_id
        );
    }

    /**
     * Displays a form to edit an existing Branch entity.
     *
     * @Route("/{chain_id}/branch/{id}/edit", name="admin_branch_edit", defaults={"_city" = "kiev", "id" = 0})
     * @Route("/{_city}/{chain_id}/branch/{id}/edit", name="admin_branch_edit_city")
     * @Template()
     */
    public function editAction($id, $chain_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var Branch
         */
        $entity = $em->getRepository('MainBundle:Branch')->find($id);
        $entitiesPhoto = $entity->getPhotos();

        $editForm = $this->createForm(new BranchType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

//        var_dump($entity->getLat());exit;
        if ($request->isMethod("POST")) {
            $editForm->bind($request);


            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrlCity('admin_branch', array('chain_id' => $chain_id)));
            }
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'entitiesPhoto' => $entitiesPhoto
        );
    }

    /**
     * Deletes a Branch entity.
     *
     * @Route("/{id}/delete", name="admin_branch_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Branch')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Branch entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_branch'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                ->add('id', 'hidden')
                ->getForm();
    }

}
