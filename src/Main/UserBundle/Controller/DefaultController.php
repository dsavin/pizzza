<?php

namespace Main\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Main\UserBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * Lists all Chain entities.
     *
     * @Route("/admin/users/", name="admin_users", defaults={"_city" = "kiev"})
     * @Route("/admin/{_city}/users/", name="admin_users_city")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainUserBundle:User')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Chain entity.
     *
     * @Route("/admin/user/new/{id}", name="admin_user_new", defaults={"_city" = "kiev", "id"="0"})
     * @Route("/admin/{_city}/user/new/{id}", name="admin_user_new_city", defaults={"id"="0"})
     * @Template()
     */
    public function editAction($_city, $id = 0)
    {
        $entity = new User();
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $entity = $em->getRepository('MainUserBundle:User')->findOneById($id);
        }

        return array(
            'entity' => $entity,
            'id' => $id
        );
    }
}
