<?php


namespace Main\Bundle\Controller\Client;
use Main\Bundle\Controller\BaseController as Controller;

use Main\Bundle\Entity\ChainRepository;
use Main\Bundle\Entity\Slider;
use Main\Bundle\Entity\Publication;
use Main\Bundle\Entity\Comment;

class PageLayoutController extends Controller
{
    public function indexDiscountsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $city = $this->getCurrentCity();
        $paginator  = $this->get('knp_paginator');

        $queryDiscouts = $em->getRepository('MainBundle:Discount')->getAllWithChainByCity($city->getId(),
                                                                                          $request->getLocale());

        $pagination = $paginator->paginate(
            $queryDiscouts,
            $this->get('request')->query->get('page', 1)/*page number*/,
            12/*limit per page*/
        );

        return $this->render('MainBundle:Client/PageLayout:discounts.html.twig',
                             array(
                                  'pagination' => $pagination
                             ));
    }

    public function indexSliderAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $city = $this->getCurrentCity();
        $entities = $em->getRepository('MainBundle:Slider')->findBy(array('lang'=>$request->getLocale(), 'city_id' => $city->getId()), array('id' => 'DESC'));

        return $this->render('MainBundle:Client/PageLayout:slider.html.twig',
                             array(
                                  'entities' => $entities
                             ));
    }

    public function indexRecommendedAction($maxRatingDelivery, $maxRatingChain)
    {
        $request = $this->getRequest();
        $city = $this->getCurrentCity();
        $em = $this->getEm();

        /** @var ChainRepository $chainRepositiory  */
        $chainRepositiory = $em->getRepository('MainBundle:Chain');

        $etities = $chainRepositiory->getRecommendByLimit($city->getId(), $request->getLocale(), 6);

        return $this->render('MainBundle:Client/PageLayout:recommended.html.twig',
                             array(
                                  'maxRatingDelivery' => $maxRatingDelivery,
                                  'maxRatingChain' => $maxRatingChain,
                                  'etities' => $etities
                             ));
    }

    public function indexPublicationAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $city = $this->getCurrentCity();
        $entities = $em->getRepository('MainBundle:Publication')->getAllWithLimit(array('lang'=>$request->getLocale(), 'city_id' => $city->getId(), 'limit' => 5));

        return $this->render('MainBundle:Client/PageLayout:publication.html.twig',
                             array(
                                 'entities' => $entities,
                                 'types' => array('news' => Publication::TYPE_NEWS, 'recipe' => Publication::TYPE_RECIPE)
                             ));
    }

    public function lastCommentAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $city = $this->getCurrentCity();
        $entities = $em->getRepository('MainBundle:Comment')->getAllWithLimit(array('lang'=>$request->getLocale(), 'city_id' => $city->getId(), 'limit' => 3));

        return $this->render('MainBundle:Client/PageLayout:lastComment.html.twig',
                             array(
                                 'entities' => $entities
                             ));
    }

    public function indexTopAction($maxRatingDelivery, $maxRatingChain)
    {
        $em = $this->getEm();
        $request = $this->getRequest();
        $city = $this->getCurrentCity();


        /** @var ChainRepository $chainRepositiory  */
        $chainRepositiory = $em->getRepository('MainBundle:Chain');

        $topDelivery = $chainRepositiory->getTopDeliveryRating($city->getId(), $request->getLocale(), 5);
        $topChains = $this->getTopChain($city->getId(), $request->getLocale(), 5);

        return $this->render('MainBundle:Client/PageLayout:top.html.twig',
                             array(
                                  'maxRatingDelivery' => $maxRatingDelivery,
                                  'maxRatingChain' => $maxRatingChain,
                                  'topChains' => $topChains,
                                  'topDelivery' => $topDelivery
                             ));
    }

    public function getTopChain($city_id, $lang, $limit)
    {
        $em = $this->getEm();
        $array = array();

        /** @var ChainRepository $chainRepositiory  */
        $chainRepositiory = $em->getRepository('MainBundle:Chain');

        $topChains = $chainRepositiory->getChainByMaxRating($city_id, $lang, $limit);
        $ids = array();

        foreach ($topChains as $v) {
            $ids[] = $v['id'];
        }

        $eteties = $chainRepositiory->findBy(array('id'=>$ids));

        foreach ($topChains as $v)
        {
            foreach ($eteties as $etity){
                if ($etity->getId() == $v['id']) {
                    $array[] = $etity;
                }
            }
        }

        return $array;
    }
}