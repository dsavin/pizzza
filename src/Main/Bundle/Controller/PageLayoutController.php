<?php


namespace Main\Bundle\Controller;
use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Cache\ApcCache;

use Main\Bundle\Entity\City;
use Main\Bundle\Entity\Chain;
use Main\Bundle\Entity\Branch;
use Main\Bundle\Entity\Comment;

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
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $city = $this->getCurrentCity();

        $cacheDriver = new ApcCache();
        $fetchCache = $cacheDriver->fetch('header_counting');

        if (!$fetchCache) {
            $comments = $em->getRepository('MainBundle:Comment')->findAll();
            $branchesCount = $em->getRepository('MainBundle:Branch')->getCountBranch($city->getId(), $request->getLocale());
            $arr = array(
                'comments' => count($comments),
                'branchesCount' => $branchesCount
            );

            $cacheDriver->save('header_counting', serialize($arr), 36000);
        } else {
            $arr = unserialize($fetchCache);
        }

        return $this->render('MainBundle:PageLayout:header.html.twig',
                             array(
                                 'active' => $active,
                                 'commentsCount' => $arr['comments'],
                                 'branchesCount' => $arr['branchesCount'],
								 'banner' => 0
                             ));
    }

    public function footerAction($active = 0)
    {

        return $this->render('MainBundle:PageLayout:footer.html.twig',
                             array(
                                  'active' => $active,
                                  'dateYear' => date('Y')
                             ));
    }

    public function sidebarAction()
    {
        $em = $this->getEm();
        $request = $this->getRequest();
        $city = $this->getCurrentCity();

        /** @var ChainRepository $chainRepositiory  */
        $chainRepositiory = $em->getRepository('MainBundle:Chain');

        $topDelivery = $chainRepositiory->getTopDeliveryRating($city->getId(), $request->getLocale(), 5);
        $topChains = $this->getTopChain($city->getId(), $request->getLocale(), 5);

        $entities = $em->getRepository('MainBundle:Comment')->getAllWithLimit(array('lang'=>$request->getLocale(), 'city_id' => $city->getId(), 'limit' => 3));

        return $this->render('MainBundle:PageLayout:sidebar.html.twig',
                             array(
                                 'topChains' => $topChains,
                                 'topDelivery' => $topDelivery,
                                 'entities' => $entities
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


    public function basketAction()
    {
        $chainAPIInfoMain = new \stdClass();
        $chainAPIInfoMain->title = '';
        $chainAPIInfoMain->delivery = '';
        $chainAPIInfoMain->discount = 0;

        return $this->render('MainBundle:PageLayout:basket.html.twig',
                             array(
                                  'partners' => array(),
                                  'items' => array(),
                                  'price' => 0,
                                  '_city' => $this->getCurrentCity(),
                                  '_locale' => $this->getRequest()->getLocale(),
                                  'chainAPIInfoMain' => $chainAPIInfoMain
                             ));
    }
}