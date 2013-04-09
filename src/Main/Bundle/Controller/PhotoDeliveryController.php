<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\PhotoDelivery;
use Main\Bundle\Form\PhotoDeliveryType;
use Main\Bundle\Entity\Chain;

/**
 * PhotoDelivery controller.
 *
 * @Route("/{_city}/admin/{chain_id}/photo_delivery")
 */
class PhotoDeliveryController extends Controller
{
    /**
     * Displays a form to edit an existing PhotoDelivery entity.
     *
     * @Route("/{id}/edit", name="admin_photo_delivery_edit")
     * @Template()
     */
    public function editAction(Request $request, $id, $_city, $chain_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entityChain = $em->getRepository('MainBundle:Chain')->find($chain_id);
        $entity = $em->getRepository('MainBundle:PhotoDelivery')->find($id);

        if (!$id || !$entity) {
            $entity = new PhotoDelivery();
        }

        $editForm = $this->createForm(new PhotoDeliveryType(), $entity);

        if ($request->isMethod("POST")) {
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $entity->setChain($entityChain);

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_photo_delivery_edit', array('id' => $entity->getId(), 'chain_id'=>$chain_id, '_city'=>$_city)));
            }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'entityChain' => $entityChain,
            'id' => $id,
            'chain_id' => $chain_id
        );
    }
}
