<?php

namespace Booking\Controller\Plugin\PayPal;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RedirectToPayPalFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new RedirectToPayPal(
            $sm->getServiceLocator()->get('Base\Manager\ConfigManager'),
            $sm->getServiceLocator()->get('Base\Manager\OptionManager'),
            $sm->getServiceLocator()->get('Booking\Service\PayPalService'));
    }

}