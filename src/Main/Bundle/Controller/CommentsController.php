<?php

namespace Main\Bundle\Controller;

use Main\Bundle\Entity\CommentBranch;
use Proxies\__CG__\Main\Bundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Chain;
use Main\Bundle\Entity\Branch;
use Main\Bundle\Entity\CommentChain;
use Main\Bundle\Entity\CommentDelivery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Branch controller.
 *
 * @Route("/admin")
 */
class CommentsController extends Controller
{

    /**
     * @Route("/chain_comments/{chain_id}/", name="admin_chain_comments", defaults={"_city" = "kiev"})
     * @Route("/{_city}/chain_comments/{chain_id}/", name="admin_chain_comments_city")
     * @Template("MainBundle:Comments:comments.html.twig")
     */
    public function commentsChainAction($chain_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $comments = $em->getRepository('MainBundle:CommentChain')->findBy(array('chain'=>$chain_id), array('id'=>'DESC'));

        return array(
            'comments' => $comments,
            'entity' => $entity,
            'type' => CommentChain::TYPE_CHAIN
        );
    }

    /**
     * @Route("/chain_comments_add/{chain_id}/", name="admin_chain_comments_add", defaults={"_city" = "kiev"})
     * @Route("/{_city}/chain_comments_add/{chain_id}/", name="admin_chain_comments_city_add")
     */
    public function commentsChainAddAction($chain_id, Request $request)
    {
        $array = array();

        if ($request->isXmlHttpRequest()) {
            /** @var EtityManger $em */
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();

            $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chain entity.');
            }

            $datetime = new \DateTime();
            $date = explode('-',$data['date']);
            $datetime->setDate($date[0], $date[1], $date[2]);

            $comment = new CommentChain();
            $comment->setData($data);
            $comment->setUserIp(ip2long($request->getClientIp()));
            $comment->setChain($entity);
            $comment->setCreatedAt($datetime);

            $em->persist($comment);
            $em->flush();

            $array['success'] = true;
            $array['date'] = $data;
        } else {
            $array['error'] = true;
        }

        return new JsonResponse($array);
    }

    /**
     * @Route("/delivery_comments/{chain_id}/", name="admin_delivery_comments", defaults={"_city" = "kiev"})
     * @Route("/{_city}/delivery_comments/{chain_id}/", name="admin_delivery_comments_city")
     * @Template("MainBundle:Comments:comments_delivery.html.twig")
     */
    public function commentsDeliveryAction($chain_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        $comments = $em->getRepository('MainBundle:CommentDelivery')->findBy(array('chain'=>$chain_id), array('id'=>'DESC'));

        return array(
            'comments' => $comments,
            'entity' => $entity,
            'type' => CommentChain::TYPE_CHAIN
        );
    }

    /**
     * @Route("/delivery_comments_add/{chain_id}/", name="admin_delivery_comments_add", defaults={"_city" = "kiev"})
     * @Route("/{_city}/delivery_comments_add/{chain_id}/", name="admin_delivery_comments_city_add")
     */
    public function commentsDeliveryAddAction($chain_id, Request $request)
    {
        $array = array();

        if ($request->isXmlHttpRequest()) {
            /** @var EtityManger $em */
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();

            $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chain entity.');
            }

            $datetime = new \DateTime();
            $date = explode('-',$data['date']);
            $datetime->setDate($date[0], $date[1], $date[2]);

            $comment = new CommentDelivery();
            $comment->setData($data);
            $comment->setUserIp(ip2long($request->getClientIp()));
            $comment->setChain($entity);
            $comment->setCreatedAt($datetime);

            $em->persist($comment);
            $em->flush();

            $array['success'] = true;
            $array['date'] = $data;
        } else {
            $array['error'] = true;
        }

        return new JsonResponse($array);
    }

    /**
     * @Route("/branch_comments/{chain_id}/{branch_id}/", name="admin_branch_comments", defaults={"_city" = "kiev"})
     * @Route("/{_city}/branch_comments/{chain_id}/{branch_id}/", name="admin_branch_comments_city")
     * @Template("MainBundle:Comments:comments_branch.html.twig")
     */
    public function commentsBranchAction($chain_id, $branch_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);
        $branch = $em->getRepository('MainBundle:Branch')->find($branch_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chain entity.');
        }

        if (!$branch) {
            throw $this->createNotFoundException('Unable to find Branch entity.');
        }

        $comments = $em->getRepository('MainBundle:CommentBranch')->findBy(array('chain'=>$chain_id, 'branch'=>$branch_id), array('id'=>'DESC'));

        return array(
            'comments' => $comments,
            'entity' => $entity,
            'branch' => $branch,
            'type' => CommentChain::TYPE_CHAIN
        );
    }

    /**
     * @Route("/branch_comments_add/{chain_id}/{branch_id}/", name="admin_branch_comments_add", defaults={"_city" = "kiev"})
     * @Route("/{_city}/branch_comments_add/{chain_id}/{branch_id}/", name="admin_branch_comments_city_add")
     */
    public function commentsBranchAddAction($chain_id, $branch_id, Request $request)
    {
        $array = array();

        if ($request->isXmlHttpRequest()) {
            /** @var EtityManger $em */
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();

            $entity = $em->getRepository('MainBundle:Chain')->find($chain_id);
            $branch = $em->getRepository('MainBundle:Branch')->find($branch_id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chain entity.');
            }

            $datetime = new \DateTime();
            $date = explode('-',$data['date']);
            $datetime->setDate($date[0], $date[1], $date[2]);

            $comment = new CommentBranch();
            $comment->setData($data);
            $comment->setUserIp(ip2long($request->getClientIp()));
            $comment->setChain($entity);
            $comment->setBranch($branch);
            $comment->setCreatedAt($datetime);

            $em->persist($comment);
            $em->flush();

            $array['success'] = true;
            $array['date'] = $data;
        } else {
            $array['error'] = true;
        }

        return new JsonResponse($array);
    }
    /**
     * @Route("/chain_comments_delete", name="admin_chain_comments_delete", defaults={"_city" = "kiev"})
     */
    public function commentsChainDeleteAction(Request $request){

        $array = array();

        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:CommentChain')->find($request->get('comment_id'));


            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            $em->remove($entity);
            $em->flush();

            $array['success'] = true;

        } else {
            $array['error'] = true;
        }


        return new JsonResponse($array);
    }



}
