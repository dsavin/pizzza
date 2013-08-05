<?php

namespace Main\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Main\Bundle\Controller\BaseController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Chain;
use Main\Bundle\Entity\CommentChain;


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
}
