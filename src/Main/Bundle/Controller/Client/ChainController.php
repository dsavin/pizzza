<?php

namespace Main\Bundle\Controller\Client;

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
     * Finds and displays a Chain entity.
     *
     * @Template()
     */
    public function showAction($url, $_locale, $_city)
    {
        $this->checkCity($_city);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Chain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
        );
    }


}
