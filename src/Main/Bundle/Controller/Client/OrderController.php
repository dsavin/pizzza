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
            foreach($items as $val){
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
            $chainId = 0;
            foreach($items as $item) {
                $price = $price+$item->price;
                if (isset($item->chain_id)) {
                    $chainId = $item->chain_id;
                }
            }

            $this->addAjaxResponce('prices', $price);
            $this->addAjaxResponce('items', $items);
            $this->addAjaxResponce('chainId', $chainId);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }

    public function sendItemsAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        if ($request->isXmlHttpRequest()) {

            $data = $request->request->get('data');
            $data['items'] = (array) json_decode($session->get('items'));

            $chain = $em->getRepository('MainBundle:Comment')->findOneById($data['items'][0]->chain_id);
            $session->set('items', json_encode(array()));

            mail('oklosovich@gmail.com', 'Заказ с Pizzza.com.ua 1', print_r($data, true));
            mail($chain->getEmail(), 'Заказ с Pizzza.com.ua 2', print_r($data, true));

            $this->addAjaxResponce('data', json_encode($data));
            $this->addAjaxResponce('result', true);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }

    public function getMenuItemsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $records = array();
        if ($request->isXmlHttpRequest()) {
            $chainId = (int)$request->request->get('chain_id');
            $items = $em->getRepository('MainBundle:Item')
                ->findBy(array(
                    'chain' => $chainId
                ));
            foreach ($items as $item) {
                if ($item->getPrice() > 1) {
                    $records[] = array(
                        'id' => $item->getId(),
                        'image' => $item->getImageName(),
                        'ingredients' => $item->getText(),
                        'price' => $item->getPrice(),
                        'title' => $item->getName(),
                        'size' => $item->getSize(),
                        'weight' => $item->getWeight()
                    );
                }
            }

            $this->addAjaxResponce('items', array('records'=>$records));
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }
}
