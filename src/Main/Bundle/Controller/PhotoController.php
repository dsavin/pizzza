<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Photo;
use Main\Bundle\Form\PhotoType;

/**
 * Photo controller.
 *
 * @Route("/{_city}/admin/{type}/{object_id}/photo")
 */
class PhotoController extends Controller
{
    /**
     * Displays a form to edit an existing Photo entity.
     *
     * @Route("/{id}/edit", name="admin_photo_edit")
     * @Template()
     */
    public function editAction(Request $request, $type, $object_id, $id, $_city)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Photo();

        if ($id) {
            $entity = $em->getRepository('MainBundle:Photo')->find($id);
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $editForm = $this->createForm(new PhotoType(), $entity);

        if ($request->isMethod("POST")) {
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $entity->setType($type);
                $entity->setObjectId($object_id);

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_photo_edit', array('id' => $id, 'type'=>$type, 'object_id'=>$object_id, '_city'=>$_city)));
            }
        }


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'type' => $type,
            'object_id' => $object_id,
            'id' => $id
        );
    }

    /**
     * Edits an existing Photo entity.
     *
     * @Route("/{id}/update", name="admin_photo_update")
     * @Method("POST")
     * @Template("MainBundle:Photo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PhotoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_photo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
}
