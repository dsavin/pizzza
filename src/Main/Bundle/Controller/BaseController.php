<?php

namespace Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Main\Bundle\Entity\Lang;
use Main\Bundle\Entity\City;

use Symfony\Component\HttpFoundation\Session\Session;

//method who use all
class BaseController extends Controller
{

    public function getLangsArray()
    {
        $return = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MainBundle:Lang')->findAll();

        foreach( $entities as $entity ){
            $return[$entity->getIso()] = $entity->getIso();
        }

        return $return;
    }


    public function getCities()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MainBundle:City')->findAll();

        return $entities;
    }

    public function getCityByUrl($url)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:City')->findOneByUrl($url);

        if (!$entity) {
            throw $this->createNotFoundException('Нету тут такой сети');
        }

        return $entity;
    }

    public function getParamsCities()
    {

        return $this->container->getParameter('cities');
    }

    public function getCurrentCity()
    {
        $_city = $this->container->get('request')->attributes->get('_city');

        return $this->getCityByUrl($_city);
    }

    public function checkCity($_city)
    {
        if (!in_array($_city, $this->getParamsCities())) {
            throw $this->createNotFoundException('Даного города нет в нашем списке');
        }
    }

    public function generateUrlCity($route, $parameters = array())
    {
        $entity = $this->getCurrentCity();
        if($entity->getUrl() !== 'kiev'){
            $parameters = $parameters+array('_city'=>$entity->getUrl());
            $route = $route.'_city';
        }

        return $this->generateUrl($route, $parameters);
    }

}
