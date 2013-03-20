<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Main\Bundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Initializes the locale based on the current request.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class LocaleListener implements EventSubscriberInterface
{
    private $router;
    private $defaultLocale;
    private $defaultCity;

    public function __construct($defaultLocale = 'ru', $defaultCity = 'kiev', RequestContextAwareInterface $router = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->router = $router;
        $this->defaultCity = $defaultCity;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        $request->setDefaultLocale($this->defaultLocale);

        $_city = $request->attributes->get('_city');



        if(!$_city) {
            $request->attributes->set('_city',$this->defaultCity);
        }
        $session->set('_city', $request->attributes->get('_city'));

        if ($locale = $request->attributes->get('_locale')) {
            $request->setLocale($locale);
            $session->set('_locale', $locale);
        }

        if (null !== $this->router) {
            $this->router->getContext()->setParameter('_locale', $request->getLocale());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the Router to have access to the _locale
            KernelEvents::REQUEST => array(array('onKernelRequest', 16)),
        );
    }
}
