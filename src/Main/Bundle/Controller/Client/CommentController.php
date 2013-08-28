<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Main\Bundle\Entity\Comment;
use Main\Bundle\Entity\CommentChain;
use Main\Bundle\Entity\CommentBranch;
use Main\Bundle\Entity\CommentDelivery;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{

    public function addCommentAction(Request $request, $item)
    {
        if ($request->isXmlHttpRequest()) {
            /** @var EtityManger $em */
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();
            $lastName = trim($data['last_name']);

            $city = $this->getCurrentCity();

            if (!empty($lastName)) {
                $this->addAjaxResponceError('Робот скатина');
                goto end;
            }
            $entityChain = $em->getRepository('MainBundle:Chain')->find($item);

            try {
                switch ($data['type']) {
                    case Comment::TYPE_CHAIN:
                        $entity = new CommentChain();
                        $this->addAjaxResponce('redirect', $this->generateUrlCity('_chain_comments',array( 'chain_url'=>$entityChain->getUrl() )));
                        break;
                    case Comment::TYPE_DELIVERY:
                        $entity = new CommentDelivery();
                        $entityChain->setRatingDelivery($entityChain->getRatingDelivery()+$data['rating']);
                        $em->persist($entityChain);
                        break;
                    case Comment::TYPE_BRANCH:
                        $entity = new CommentBranch();
                        $entityBranch = $em->getRepository('MainBundle:Branch')->find($data['branch']);
                        $entityBranch->setRating($entityBranch->getRating()+$data['rating']);
                        $entity->setBranch($entityBranch);
                        $em->persist($entityBranch);
                        break;
                }
                $entity->setChain($entityChain);
            } catch (Exception $e) {
                $this->addAjaxResponceError('Что то не то с сетю или отделением');
                goto end;
            }
            $entity->setData($data);
            $entity->setUserIp(ip2long($request->getClientIp()));

            $validator = $this->get('validator');
            $errors = $validator->validate($entity);

            if ( $errors->count() ) {
                $messages = array();
                foreach ($errors as $error) {
                    $messages[$error->getPropertyPath()] = $error->getMessage();
                }
                $this->addAjaxResponceError($messages);
                goto end;
            }

            $em->persist($entity);
            $em->flush();

            $htmlView = $this->renderView('MainBundle:Client:comment_item.html.twig',
                                        array(
                                             'comment' => $entity,
                                             '_city' => $city->getUrl(),
                                             '_locale' => $request->getLocale()
                                        ));

            $this->addAjaxResponce('success', true);
            $this->addAjaxResponce('html', $htmlView);
            $this->addAjaxResponce('id', $entity->getId());

        }
        else {
            $this->addAjaxResponceError('Не яаксовый запрос');
        }
        end:

        return new JsonResponse($this->getAjaxResponce());
    }
}
