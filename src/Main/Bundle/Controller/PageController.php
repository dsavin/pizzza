<?php

namespace Main\Bundle\Controller;

use Doctrine\Tests\Common\Annotations\Fixtures\Controller;
use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Page;
use Main\Bundle\Form\PageType;

/**
 * Page controller.
 *
 */
class PageController extends BaseController
{
    /**
     * Lists all Page entities.
     *
     * @Route("/admin/page/", name="admin_page", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/page/", name="admin_page_city")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Page')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/admin/page/new", name="admin_page_new", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/page/new", name="admin_page_new_city")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createForm(new PageType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/admin/page/create", name="admin_page_create", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/page/create", name="admin_page_create_city")
     * @Method("POST")
     * @Template("MainBundle:Page:new.html.twig")
     */
    public function createAction($_city, Request $request)
    {
        $entity  = new Page();
        $form = $this->createForm(new PageType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setLang($request->getLocale());
            $entity->setCityId($this->getCurrentCity()->getId());

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrlCity('admin_page', array('_city' => $_city)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/admin/page/{id}/edit", name="admin_page_edit", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/page/{id}/edit", name="admin_page_edit_city")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createForm(new PageType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Page entity.
     *
     * @Route("/admin/page/{id}/update", name="admin_page_update", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/page/{id}/update", name="admin_page_update_city")
     * @Method("POST")
     * @Template("MainBundle:Page:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createForm(new PageType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
}
