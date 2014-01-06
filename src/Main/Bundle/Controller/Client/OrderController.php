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
            $items = (array)json_decode($session->get('items'));
            $newItems = array();
            foreach($items as $key => $val){
                if ($val->id != $itemId) {
                    $newItems[] = $val;
                }
            }

            $session->set('items', json_encode($newItems));
            $this->addAjaxResponce('remove_item', $itemId);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }

    public function getItemsAction(Request $request)
    {
        $session = $request->getSession();

        $items = json_decode($session->get('items'));
        if ($request->isXmlHttpRequest()) {
            $price = 0;
            $discount = 0;
            $chainId = 0;
            foreach($items as $item) {
                $price = $price+$item->price;
                if (isset($item->discount)) {
                    $discount = $item->discount;
                }
                if (isset($item->chain_id)) {
                    $chainId = $item->chain_id;
                }
            }

            $chainAPIInfo = (object)$this->getInfoByIdAPI($chainId);

            $this->addAjaxResponce('prices', $price);
            $this->addAjaxResponce('items', $items);
            $this->addAjaxResponce('discount', $discount);
            $this->addAjaxResponce('chainId', $chainId);
            $this->addAjaxResponce('delivery_text', isset($chainAPIInfo->delivery)?$chainAPIInfo->delivery:'');
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('json'=>json_encode($data), 'source' => 'pizzza'));
            curl_setopt($ch, CURLOPT_HEADER, false);
            $result = curl_exec($ch);

            $session = $request->getSession();
            $items = json_decode($session->get('items'));
            $price = 0;
            foreach($items as $item) {
                $price = $price+$item->price;
            }

            $data['items'] = $session->get('items');
            $session->set('items', json_encode(array()));

            mail('oklosovich@gmail.com', 'Order - '.$price, print_r($data, true));

            $this->addAjaxResponce('data', json_encode($data));
            $this->addAjaxResponce('result', $result);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }

    public function getMenuItemsAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {

            $chainId = (int)$request->request->get('chain_id');

            $cacheDriver = new ApcCache();
            $fetchCache = $cacheDriver->fetch('1001_pizza_api_pizzeria_'.$chainId);

            if (!$fetchCache) {
                $contentPre = $this->get_data('http://1001pizza.com.ua/api/search/?pizzeria_id='. $chainId);
                $content = json_decode($contentPre);
                if (!empty($content->records)) {
                    $cacheDriver->save('1001_pizza_api_pizzeria_'.$chainId, serialize($content), 36000);
                } else {
                    $content->records = array();
                }
            } else {
                $content = unserialize($fetchCache);
            }

            $this->addAjaxResponce('items', $content);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }
}
