<?php

namespace Main\Bundle\Controller;

use JMS\DiExtraBundle\Tests\Functional\AppKernel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Main\Bundle\Entity\Lang;
use Main\Bundle\Entity\City;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Acl\Exception\Exception;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Doctrine\Common\Cache\ApcCache;

//method who use all
class BaseController extends Controller
{

    private $ajaxResponce = array();

    public function getAjaxResponce()
    {

        return $this->ajaxResponce;
    }

    public function addAjaxResponceError($message)
    {
        $this->ajaxResponce['errors'] = $message;
    }

    public function isAjaxResponceHasError()
    {
        if ( isset($this->ajaxResponce['error']) && !empty($this->ajaxResponce['error']) ) {
            return true;
        }

        return false;
    }

    public function addAjaxResponce($key, $value)
    {
        $this->ajaxResponce[$key] = $value;
    }

    public function getLangsArray()
    {
        $return = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MainBundle:Lang')->findAll();

        foreach( $entities as $entity ){
            $return[$entity->getIso()] = $entity->getIso();
        }

        return $return;
    }


    public function getCities()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MainBundle:City')->findAll();

        return $entities;
    }

    public function getCityByUrl($url)
    {

        $cacheDriver = new ApcCache();
        $fetchCache = $cacheDriver->fetch('current_city_'.$url);

        if (!$fetchCache) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:City')->findOneByUrl($url);

            if (!$entity) {
                throw $this->createNotFoundException('Нету тут такой сети');
            } else {
                $cacheDriver->save('current_city_'.$url, serialize($entity));
            }
        } else {
            $entity = unserialize($fetchCache);
        }

        return $entity;
    }

    public function getParamsCities()
    {

        return $this->container->getParameter('cities');
    }

    public function getCurrentCity()
    {
        $_city = $this->container->get('request')->attributes->get('_city');

        return $this->getCityByUrl($_city);
    }

    public function checkCity($_city)
    {
        if (!in_array($_city, $this->getParamsCities())) {
            throw $this->createNotFoundException('Даного города нет в нашем списке');
        }
    }

    public function generateUrlCity($route, $parameters = array())
    {
        $entity = $this->getCurrentCity();
        if($entity->getUrl() !== 'kiev'){
            $parameters = $parameters+array('_city'=>$entity->getUrl());
            $route = $route.'_city';
        }

        return $this->generateUrl($route, $parameters);
    }

    public function getEm()
    {

        return $this->getDoctrine()->getEntityManager();
    }

    /**
     * @return array
     * @Route("/admin/cache_clear", name="admin_clear_product_cache")
     */
    public function clearCacheConsoleAction()
    {
        $input = new ArgvInput(array('cache:clear','--env=prod','--no-debug'));
        $env = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'dev');
        $debug = getenv('SYMFONY_DEBUG') !== '0' && !$input->hasParameterOption(array('--no-debug', '')) && $env !== 'prod';

        $kernel = new \AppKernel($env, $debug);
        $application = new Application($kernel);
        ob_start();
        $o = ob_get_clean();
        $application->run($input);

        return new JsonResponse($o);
    }
}
