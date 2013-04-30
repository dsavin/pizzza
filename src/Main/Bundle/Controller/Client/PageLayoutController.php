<?php


namespace Main\Bundle\Controller\Client;
use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;


class PageLayoutController extends Controller
{
    public function indexDiscountsAction()
    {

        return $this->render('MainBundle:Client/PageLayout:discounts.html.twig',
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

        return $this->render('MainBundle:Client/PageLayout:recommended.html.twig',
                             array(
                             ));
    }

    public function indexPublicationAction()
    {

        return $this->render('MainBundle:Client/PageLayout:publication.html.twig',
                             array(
                             ));
    }

    public function lastCommentAction()
    {

        return $this->render('MainBundle:Client/PageLayout:lastComment.html.twig',
                             array(
                             ));
    }
}