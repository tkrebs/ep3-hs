<?php

namespace Booking\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PayPalServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new PayPalService(
            $sm->get('Base\Manager\ConfigManager'),
            $sm->get('Base\Manager\OptionManager'));
    }

}