<?php


namespace Main\Bundle\Controller\Client;
use Main\Bundle\Controller\BaseController as Controller;

use Main\Bundle\Entity\ChainRepository;
use Main\Bundle\Entity\Slider;



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
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $city = $this->getCurrentCity();
        $entities = $em->getRepository('MainBundle:Slider')->findBy(array('lang'=>$request->getLocale(), 'city_id' => $city->getId()));

        return $this->render('MainBundle:Client/PageLayout:slider.html.twig',
                             array(
                                  'entities' => $entities
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

    public function indexTopAction($maxRatingDelivery, $maxRatingChain)
    {
        $em = $this->getEm();
        $request = $this->getRequest();
        $city = $this->getCurrentCity();


        /** @var ChainRepository $chainRepositiory  */
        $chainRepositiory = $em->getRepository('MainBundle:Chain');

        $topChains = $chainRepositiory->getChainByMaxRating($city->getId(), $request->getLocale(), 5);
        $ids = array();

        foreach ($topChains as $v) {
            $ids[] = $v['id'];
        }
        $topChains = $chainRepositiory->findBy(array('id'=>$ids));

        return $this->render('MainBundle:Client/PageLayout:top.html.twig',
                             array(
                                  'maxRatingDelivery' => $maxRatingDelivery,
                                  'maxRatingChain' => $maxRatingChain,
                                  'topChains' => $topChains
                             ));
    }
}