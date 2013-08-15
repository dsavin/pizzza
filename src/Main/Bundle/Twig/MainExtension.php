<?php

namespace Main\Bundle\Twig;

use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\Request;

use Main\Bundle\Entity\City;
use Main\Bundle\Entity\Branch;
use Main\Bundle\Entity\Chain;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\ApcCache;

class MainExtension extends \Twig_Extension
{
    private $em;
    private $container;
    private $request;
    private $route;

    private $_city;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->request = $this->container->get('request');
        $this->route = $this->container->get('router');
        $this->_city = trim($this->request->attributes->get('_city'));
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'current_city'                    => new \Twig_Function_Method($this, 'currentCity'),
            'current_chain'                   => new \Twig_Function_Method($this, 'currentChain'),
            'path_city'                       => new \Twig_Function_Method($this, 'pathCity'),
            'get_max_rating_by_city'          => new \Twig_Function_Method($this, 'getMaxRating'),
            'get_max_rating_delivery_by_city' => new \Twig_Function_Method($this, 'getMaxDeliveryRating'),
            'get_max_rating_chain_by_city'    => new \Twig_Function_Method($this, 'getMaxChainRating'),
            'get_count_comment_by_chain'      => new \Twig_Function_Method($this, 'getCountCommentByChain')
        );
    }

//    public function getFilters()
//    {
//        return array(
//            'current_city' => new \Twig_Filter_Method($this, 'currentCity'),
//        );
//    }

    public function currentCity()
    {
        $cacheDriver = new ApcCache();
        $fetchCache = $cacheDriver->fetch('current_city_'.$this->_city);

        if (!$fetchCache) {
            $entity = $this->em->getRepository('MainBundle:City')->findOneByUrl($this->_city);

            if (!$entity) {
                $entity = $this->em->getRepository('MainBundle:City')->findOneByUrl("kiev");
            }
            $cacheDriver->save('current_city_'.$this->_city, serialize($entity));
        } else {
            $entity = unserialize($fetchCache);
        }

        return $entity;
    }

    public function pathCity($route, $parameters = array(), $absolute = false)
    {
        if (empty($this->_city)) {
            $this->_city = $this->request->getSession()->get('_city');
        }

        if ($this->_city !== 'kiev') {
            $parameters = array('_city' => $this->_city) + $parameters;
            $route = $route . '_city';
        }

        return $this->route->generate($route, $parameters, $absolute);
    }

    public function currentChain()
    {
        $entity = $this->em->getRepository('MainBundle:Chain')->findOneById($this->request->attributes->get('chain_id'));

        return $entity;
    }

    public function getName()
    {
        return 'main_extension';
    }

    public function getMaxRating()
    {
        $entityCity = $this->currentCity();
        $rating = 0;
        $result = $this->em->createQuery('SELECT b FROM MainBundle:Branch b
                JOIN b.chain c
                WHERE c.city_id = :city_id
                AND b.lang = :lang
                ORDER BY b.rating DESC
                ')
            ->setMaxResults(1)
            ->setParameter('city_id', $entityCity->getId())
            ->setParameter('lang', 'ru')
            ->getResult();
        if ($result) {
            $rating = $result[0]->getRating();
        }

        return $rating;
    }

    public function getMaxDeliveryRating()
    {
        $entityCity = $this->currentCity();
        $rating = 0;
        $result = $this->em->createQuery('
                SELECT c.rating_delivery as max_rating FROM MainBundle:Chain c
                WHERE c.city_id = :city_id
                AND c.lang = :lang
                AND  ( c.type = 3 OR c.type = 1 )
                ORDER BY max_rating DESC
                ')
            ->setMaxResults(1)
            ->setParameter('city_id', $entityCity->getId())
            ->setParameter('lang', 'ru')
            ->getResult();

        if (!empty($result)) {
            $rating = $result[0]['max_rating'];
        }

        return $rating;
    }

    public function getMaxChainRating()
    {
        $entityCity = $this->currentCity();
        $rating = 0;
        $result = $this->em->createQuery('
                SELECT SUM(b.rating) as smm FROM MainBundle:Chain c
                JOIN c.branchs b
                WHERE c.city_id = :city_id
                AND c.lang = :lang
                AND b.lang = :lang
                AND b.parent is null
                AND  ( c.type = 3 OR c.type = 2 )
                GROUP BY c.id
                ORDER BY smm DESC
                ')

            ->setMaxResults(1)

            ->setParameter('city_id', $entityCity->getId())
            ->setParameter('lang', 'ru')

            ->getResult();

        if ($result) {
            $rating = $result[0]['smm'];
        }

        return $rating;
    }

    public function getCountCommentByChain($chain_id)
    {
        $result = $this->em->createQuery('
                SELECT COUNT(comChain) as count_comment FROM MainBundle:CommentChain comChain
                JOIN comChain.chain chainChian
                WHERE chainChian.id = :chain_chain_id
                ')
            ->setMaxResults(1)
            ->setParameter('chain_chain_id', $chain_id)
            ->getResult();

        $count = $result[0]['count_comment'];

        $result = $this->em->createQuery('
                SELECT COUNT(comChain) as count_comment FROM MainBundle:CommentDelivery comChain
                JOIN comChain.chain chainChian
                WHERE chainChian.id = :chain_chain_id
                ')
            ->setMaxResults(1)
            ->setParameter('chain_chain_id', $chain_id)
            ->getResult();

        return $count+$result[0]['count_comment'];
    }

}