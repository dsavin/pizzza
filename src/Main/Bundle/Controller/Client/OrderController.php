<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Main\Bundle\Entity\ChainRepository;
use Main\UserBundle\Entity\User;
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
            $user = $request->request->get('user');
            $data['items'] = (array) json_decode($session->get('items'));

            $chain = $em->getRepository('MainBundle:Chain')->findOneById($data['items'][0]->chain_id);
            $session->set('items', json_encode(array()));

           /* $data['items']['user'] = array(
                $data['name'],
                $data['phone']
            );*/

            $message = '';
            $price = 0;

          foreach($data['items'] as $k => $item) {
                //var_dump($item);

                $message .= 'Название пиццы: <b>' . $item->title . '</b><br>';
                $message .= 'ID пиццы: <b>' . $item->id . '</b><br>';
                $message .= 'Вес: <b>' . $item->weight . '</b><br>';
                $message .= 'Размер: <b>' . $item->size . '</b><br>';
                $message .= 'Цена: <b>' . $item->price . '</b><br>';
                $message .= 'Ингредиенты: <b>' . $item->ingredients . '</b><br>';
                $message .= 'Скидка: <b>' . $item->discount . '</b><br>';
                $message .= '<hr>';

                $price = $price + intval($item->price);

            }

            $message .= '<br><br><br><b>Всего к оплате со скидкой:</b> ' . $price . '<br>';
            $message .= 'Время заказа: ' . date('H:i:s d.m.Y') . '<br>';
            $message .= 'Получатель: ' . $data['name'] . '<br>';
            $message .= 'Телфон: ' . $data['phone']  . '<br>';



            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: info@pizzza.com.ua' . "\r\n";
            mail($chain->getEmail(), 'Заказ с Pizzza.com.ua', $message, $headers);
            mail('info@pizzza.com.ua', '[ДУБЛИКАТ] Заказ с Pizzza.com.ua', $message, $headers);
            $userModel = $this->setUserData($user, $data);


            $this->addAjaxResponce('data', json_encode($data));
            $this->addAjaxResponce('result', true);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }

    public function setUserData($userD, $data)
    {
        $em = $this->getDoctrine()->getManager();
        $repUser = $em->getRepository('MainUserBundle:User');
        $id = $userD['network_id'];
        switch ( $userD['network'] ) {
            case "facebook":
                $user = $repUser->findOneBy(array(
                                                 'facebook_id' => $id
                                            ));
                break;
            case "google":
                $user = $repUser->findOneBy(array(
                                                 'google_id' => $id
                                            ));
                break;
            case "vk":
                $user = $repUser->findOneBy(array(
                                                 'vk_id' => $id
                                            ));
                break;
            case "twitter":
                $user = $repUser->findOneBy(array(
                                                 'twitter_id' => $id
                                            ));
                break;
        }

        if (!isset($user) || !$user) {
            $user = new User();
            $user->setUsername($data['name']);
            $user->setPassword($data['name']);
            $user->setEmail($data['name']);
        }
        $user->setPhone($data['phone']);
        $user->setName($data['name']);

        $em->persist($user);
        $em->flush();
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

    public function getUserSocAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $data = (array) $request->request->all();

            $id = 0;
            $repUser = $em->getRepository('MainUserBundle:User');

            switch ( $data['network'] ) {
                case "facebook":
                    $id = $data['data']['id'];
                    $user = $repUser->findOneBy(array(
                                                     'facebook_id' => $id
                                                ));

                    if (!$user) {
                        $user = new User();
                        $user->setFacebookId($id);
                        $user->setImageUser($data['data']['thumbnail']);
                        $user->setName($data['data']['first_name']);
                        $user->setUsername($user->getName());
                        $user->setPassword($user->getName());
                        $user->setEmail($user->getName());
                        $em->persist($user);
                        $em->flush();
                    }

                    break;
                case "google":
                    $id = $data['data']['id'];
                    $user = $repUser->findOneBy(array(
                                                     'google_id' => $id
                                                ));

                    if (!$user) {
                        $user = new User();
                        $user->setGoogleId($id);
                        $user->setImageUser($data['data']['picture']);
                        $user->setName($data['data']['name']);
                        $user->setUsername($user->getName());
                        $user->setPassword($user->getName());
                        $user->setEmail($user->getName());
                        $em->persist($user);
                        $em->flush();
                    }
                    break;
                case "vk":
                    $id = $data['data']['uid'];
                    $user = $repUser->findOneBy(array(
                                                     'vk_id' => $id
                                                ));

                    if (!$user) {
                        $user = new User();
                        $user->setVkId($id);
                        $user->setImageUser($data['data']['photo_big']);
                        $user->setName($data['data']['first_name']);
                        $user->setUsername($user->getName());
                        $user->setPassword($user->getName());
                        $user->setEmail($user->getName());
                        $em->persist($user);
                        $em->flush();
                    }
                    break;
                case "twitter":
                    $id = $data['data']['id'];
                    $user = $repUser->findOneBy(array(
                                                     'twitter_id' => $id
                                                ));

                    if (!$user) {
                        $user = new User();
                        $user->setTwitterId($id);
                        $user->setImageUser($data['data']['thumbnail']);
                        $user->setName($data['data']['name']);
                        $user->setUsername($user->getName());
                        $user->setPassword($user->getName());
                        $user->setEmail($user->getName());
                        $em->persist($user);
                        $em->flush();
                    }
                    break;
            }

            if (!$user) {
                $user = new User();
            }

            $params = array(
                'id' => $id,
                'name' => $user->getName(),
                'img' => is_null($user->getImageUser())?false:$user->getImageUser(),
                'phone' => is_null($user->getPhone())?false:$user->getPhone()
            );

            $this->addAjaxResponce('data', $params);
        } else {
            $this->addAjaxResponceError("Не аяксовый запрос");
        }

        return new JsonResponse($this->getAjaxResponce());
    }
}
