<?php


namespace Main\Bundle\Controller;
use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;

use Main\Bundle\Entity\City;
use Main\Bundle\Entity\Chain;

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
        $em = $this->getEm();
        $request = $this->getRequest();
        $city = $this->getCurrentCity();

        /** @var ChainRepository $chainRepositiory  */
        $chainRepositiory = $em->getRepository('MainBundle:Chain');

        $topDelivery = $chainRepositiory->getTopDeliveryRating($city->getId(), $request->getLocale(), 5);
        $topChains = $this->getTopChain($city->getId(), $request->getLocale(), 5);

        return $this->render('MainBundle:PageLayout:sidebar.html.twig',
                             array(
                                 'topChains' => $topChains,
                                 'topDelivery' => $topDelivery
                             ));
    }

    public function getTopChain($city_id, $lang, $limit)
    {
        $em = $this->getEm();

        /** @var ChainRepository $chainRepositiory  */
        $chainRepositiory = $em->getRepository('MainBundle:Chain');

        $topChains = $chainRepositiory->getChainByMaxRating($city_id, $lang, $limit);
        $ids = array();

        foreach ($topChains as $v) {
            $ids[] = $v['id'];
        }

        return $chainRepositiory->findBy(array('id'=>$ids));
    }
}