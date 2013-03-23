<?php


namespace Main\Bundle\Controller;
use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;

use Main\Bundle\Entity\City;

class PageLayoutController extends Controller
{
    public function cityAction($_city)
    {

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MainBundle:City')->findAll();

        return $this->render('MainBundle:PageLayout:cities.html.twig',
            array(
                'entities' => $entities,
                '_city' => $_city
            ));
    }

    public function headerAction($active = 0)
    {

        return $this->render('MainBundle:PageLayout:header.html.twig',
                             array(
                                  'active' => $active
                             ));
    }

    public function footerAction($active = 0)
    {

        return $this->render('MainBundle:PageLayout:footer.html.twig',
                             array(
                                  'active' => $active
                             ));
    }

    public function sidebarAction()
    {

        return $this->render('MainBundle:PageLayout:sidebar.html.twig',
                             array(
                             ));
    }
}