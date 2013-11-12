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

    public function removeItemAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $session = $request->getSession();

            $itemId = $request->request->get('item_id');
            $items = json_decode($session->get('items'));

            foreach($items as $key => $val){
                if ($val->id == $itemId) {
                    unset($items[$key]);
                }
            }

            $session->set('items', json_encode($items));
            $this->addAjaxResponce('remove_item', $itemId);

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

    public function sendItemsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {

            $data = $request->request->get('data');

            $ch = curl_init('http://1001pizza.com.ua/api/order/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('json'=>json_encode($data)));
            curl_setopt($ch, CURLOPT_HEADER, false);
            $result = curl_exec($ch);

            $session = $request->getSession();
            $session->set('items', json_encode(array()));

            mail('oklosovich@gmail.com', 'Order', print_r($data, true));

            $this->addAjaxResponce('data', json_encode($data));
            $this->addAjaxResponce('result', $result);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }
}
