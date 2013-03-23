<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\Bundle\Entity\Photo;
use Main\Bundle\Entity\Branch;

use Main\Bundle\Form\PhotoType;

/**
 * Photo controller.
 *
 * @Route("/{_city}/admin/{branch_id}/photo")
 */
class PhotoController extends Controller
{
    /**
     * Displays a form to edit an existing Photo entity.
     *
     * @Route("/{id}/edit", name="admin_photo_edit")
     * @Template()
     */
    public function editAction(Request $request, $branch_id, $id, $_city)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Photo();

        $entityBranch = $em->getRepository('MainBundle:Branch')->find($branch_id);

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
                $entity->setBranch($entityBranch);

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_photo_edit', array('id' => $entity->getId(), 'branch_id'=>$branch_id, '_city'=>$_city)));
            }
        }

        $url = $this->generateUrlCity('admin_branch_edit', array(
                                                                'id'=> $entityBranch->getId(),
                                                                'chain_id' => $entityBranch->getChain()->getId(),
                                                                '_locale'=>$request->getLocale(),
                                                                '_city'=>$_city));

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'branch_id' => $branch_id,
            'id' => $id,
            'url' => $url
        );
    }
}
