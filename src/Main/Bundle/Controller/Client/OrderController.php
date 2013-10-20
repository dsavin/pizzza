<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Main\Bundle\Entity\ChainRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Doctrine\Common\Cache\ApcCache;

/**
 * Order controller.
 *
 */
class OrderController extends Controller
{


    public function addItemToBasketAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $items = json_decode($session->get('items'));
        if ($request->isXmlHttpRequest()) {
            $item = $request->request->get('item');
            $items[] = $item;
            $session->set('items', json_encode($items));

            $this->addAjaxResponce('item', $item);

        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }


    public function getItemsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $items = json_decode($session->get('items'));
        if ($request->isXmlHttpRequest()) {

            $this->addAjaxResponce('items', $items);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }
}
