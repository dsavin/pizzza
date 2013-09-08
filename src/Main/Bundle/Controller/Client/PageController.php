<?php

namespace Main\Bundle\Controller\Client;

use Main\Bundle\Controller\BaseController as Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\Bundle\Entity\Chain;
use Main\Bundle\Entity\Publication;

/**
 * Page controller.
 *
 */
class PageController extends Controller
{

    /**
     * Index page
     *
     * @Template()
     */
    public function indexAction($_city, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $entity = $em->getRepository('MainBundle:Page')->findOneBy(array(
                                                                        'name'    => 'index',
                                                                        'city_id' => $city->getId(),
                                                                        'lang'    => $request->getLocale()
                                                                   ));
        if (!$entity) {
            throw $this->createNotFoundException('Нету тут такой Страницы');
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays branches on map.
     *
     * @Template()
     */
    public function mapAction($_city, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);

        $entity = $em->getRepository('MainBundle:Page')->findOneBy(array(
                                                                        'name'    => 'map',
                                                                        'city_id' => $city->getId(),
                                                                        'lang'    => $request->getLocale()
                                                                   ));
        if (!$entity) {
            throw $this->createNotFoundException('Нету тут такой Страницы');
        }

        $chains = $em->getRepository('MainBundle:Chain')->findBy(array(
                                                                        'city_id' => $city->getId(),
                                                                        'lang'    => $request->getLocale()
                                                                   ));

        return array(
            'entity' => $entity,
            'chains' => $chains
        );
    }

    /**
     * Displays sitemap
     *
     * @Template()
     */
    public function sitemapAction($_city, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->getCityByUrl($_city);
        $urls = array();

        $chains = $em->getRepository('MainBundle:Chain')->findBy(array(
            'city_id' => $city->getId(),
            'lang'    => $request->getLocale()
        ));

        $urls[] = $this->generateUrlCity('_page_index', array());
        $urls[] = $this->generateUrlCity('_page_map', array());
        $urls[] = $this->generateUrlCity('_news_list', array());
        $urls[] = $this->generateUrlCity('_recipe_list', array());
        $urls[] = $this->generateUrlCity('_discounts_list', array());
        $urls[] = $this->generateUrlCity('_delivery_list', array());
        $urls[] = $this->generateUrlCity('_chain_list', array());
        $urls[] = $this->generateUrlCity('_item_all', array());

        foreach ($chains as $chain) {
            $urls[] = $this->generateUrlCity('_chain_single', array(
                'chain_url' => $chain->geturl()
            ));
            $urls[] = $this->generateUrlCity('_chain_comments', array(
                'chain_url' => $chain->geturl()
            ));
            $urls[] = $this->generateUrlCity('_chain_discounts_list', array(
                'chain_url' => $chain->geturl()
            ));

            if ($chain->getType() == Chain::TYPE_BOTH) {
                $urls[] = $this->generateUrlCity('_branches_all', array(
                    'chain_url' => $chain->geturl()
                ));
                $urls[] = $this->generateUrlCity('_chain__delivery_single', array(
                    'chain_url' => $chain->geturl()
                ));
            } elseif ($chain->getType() == Chain::TYPE_BRANCHES) {
                $urls[] = $this->generateUrlCity('_branches_all', array(
                    'chain_url' => $chain->geturl()
                ));
            } elseif ($chain->getType() == Chain::TYPE_DELIVERY) {
                $urls[] = $this->generateUrlCity('_chain__delivery_single', array(
                    'chain_url' => $chain->geturl()
                ));
            }


            foreach ($chain->getBranchsByLocale($request->getLocale()) as $branch) {
                $urls[] = $this->generateUrlCity('_branch_single', array(
                    'chain_url' => $chain->geturl(),
                    'branch_url' => $branch->geturl()
                ));
            }
            foreach ($chain->getDiscountsByLocale($request->getLocale()) as $discount) {
                $urls[] = $this->generateUrlCity('_discount_single', array(
                    'chain_url' => $chain->geturl(),
                    'dis_url' => $discount->geturl()
                ));
            }
            foreach ($chain->getItems() as $item) {
                $urls[] = $this->generateUrlCity('_item_single', array(
                      'chain_url' => $chain->geturl(),
                      'item_url' => $item->geturl()
                 ));
            }
        }

        $publications = $em->getRepository('MainBundle:Publication')->findBy(array(
            'city_id' => $city->getId(),
            'lang'    => $request->getLocale()
        ));

        foreach ($publications as $publication) {
            if ($publication->getType() == Publication::TYPE_NEWS) {
                $urls[] = $this->generateUrlCity('_news_single', array(
                    'url' => $publication->geturl()
                ));
            } elseif ($publication->getType() == Publication::TYPE_RECIPE) {
                $urls[] = $this->generateUrlCity('_recipe_single', array(
                    'url' => $publication->geturl()
                ));
            }

        }

        return array(
            'urls' => $urls
        );
    }
}
