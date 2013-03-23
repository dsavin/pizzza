<?php

    namespace Main\Bundle\Twig;

    use Doctrine\ORM\EntityManager;

    use Symfony\Component\HttpFoundation\Request;

    use Main\Bundle\Entity\City;
    use Main\Bundle\Entity\Branch;
    use Main\Bundle\Entity\Chain;

    use Symfony\Component\DependencyInjection\ContainerInterface;

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
                'current_city'           => new \Twig_Function_Method($this, 'currentCity'),
                'current_chain'          => new \Twig_Function_Method($this, 'currentChain'),
                'path_city'              => new \Twig_Function_Method($this, 'pathCity'),
                'get_max_rating_by_city' => new \Twig_Function_Method($this, 'getMaxRating'),

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
            $entity = $this->em->getRepository('MainBundle:City')->findOneByUrl($this->_city);

            if (!$entity) {
                $entity = $this->em->getRepository('MainBundle:City')->findOneByUrl("kiev");
            }

            return $entity;
        }

        public function pathCity($route, $parameters = array(), $absolute = false)
        {
            if (empty($this->_city)) {
                $this->_city = $this->request->getSession()->get('_city');
            }

            if ($this->_city !== 'kiev') {
                $parameters =  array('_city' => $this->_city)+$parameters;
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

    }