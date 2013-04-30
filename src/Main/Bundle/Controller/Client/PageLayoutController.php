<?php


namespace Main\Bundle\Controller\Client;
use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;


class PageLayoutController extends Controller
{
    public function indexTopAction()
    {

        return $this->render('MainBundle:Clinet/PageLayout:top.html.twig',
                             array(
                             ));
    }

    public function indexSliderAction()
    {

        return $this->render('MainBundle:Client/PageLayout:slider.html.twig',
                             array(
                             ));
    }

    public function indexRecommendedAction()
    {

        return $this->render('MainBundle:PageLayout:sidebar.html.twig',
                             array(
                             ));
    }
}