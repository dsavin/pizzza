<?php

namespace Main\Bundle\Controller;

use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Slider;
use Main\Bundle\Form\SliderType;

/**
 * Slider controller.
 *
 */
class SliderController extends Controller
{
    /**
     * Lists all Slider entities.
     *
     * @Route("/admin/slider/", name="admin_slider", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/slider/", name="admin_slider_city")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Slider')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Slider entity.
     *
     * @Route("/admin/slider/new", name="admin_slider_new", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/slider/new", name="admin_slider_new_city")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Slider();
        $form = $this->createForm(new SliderType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Slider entity.
     *
     * @Route("/admin/slider/create", name="admin_slider_create", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/slider/create", name="admin_slider_create_city")
     * @Method("POST")
     * @Template("MainBundle:Slider:new.html.twig")
     */
    public function createAction($_city, Request $request)
    {
        $entity = new Slider();
        $form = $this->createForm(new SliderType(), $entity);
        $form->bind($request);
        $city = $this->getCityByUrl($_city);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCityId($city->getId());
            $entity->setLang($request->getLocale());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrlCity('admin_slider', array('city' => $_city)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Slider entity.
     *
     * @Route("/admin/slider/{id}/edit", name="admin_slider_edit", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/slider/{id}/edit", name="admin_slider_edit_city")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Slider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slider entity.');
        }

        $editForm = $this->createForm(new SliderType(), $entity);

        return array(
            'entity'    => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Slider entity.
     *
     * @Route("/admin/slider/{id}/update", name="admin_slider_update", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/slider/{id}/update", name="admin_slider_update_city")
     * @Method("POST")
     * @Template("MainBundle:Slider:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Slider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slider entity.');
        }

        $editForm = $this->createForm(new SliderType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrlCity('admin_slider_edit', array('id' => $id)));
        }

        return array(
            'entity'    => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Slider entity.
     *
     * @Route("/admin/slider/{id}/delete", name="admin_slider_delete", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/slider/{id}/delete", name="admin_slider_delete_city")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Slider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slider entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrlCity('admin_slider', array()));
    }
}
