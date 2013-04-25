<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController as Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Publication;
use Main\Bundle\Form\PublicationType;

use Symfony\Component\HttpFoundation\Tests\RequestContentProxy;

/**
 * Publication controller.
 *
 * @Route("/admin")
 */
class PublicationController extends Controller
{
    /**
     * Lists all Publication entities.
     *
     * @Route("/publication", name="admin_publication", defaults={"_city" = "kiev"})
     * @Route("/{_city}/publication", name="admin_publication_city")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Publication')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Publication entity.
     *
     * @Route("/publication/new", name="admin_publication_new", defaults={"_city" = "kiev"})
     * @Route("/{_city}/publication/new", name="admin_publication_new_city")
     * @Template()
     */
    public function newAction(Request $request, $_city)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Publication();
        $form   = $this->createForm(new PublicationType(), $entity);

        if ($request->isMethod("POST")) {
            $form->bind($request);

            if ($form->isValid()) {
                $entity->setLang($request->getLocale());

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrlCity('admin_publication', array('_city' => $_city, '_locale' => $request->getLocale())));
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing Publication entity.
     *
     * @Route("/{id}/publication/edit", name="admin_publication_edit", defaults={"_city" = "kiev"})
     * @Route("/{_city}/{id}/publication/edit", name="admin_publication_edit_city")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }

        $editForm = $this->createForm(new PublicationType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Publication entity.
     *
     * @Route("/{id}/delete", name="admin_publication_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Publication')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Publication entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_publication'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
