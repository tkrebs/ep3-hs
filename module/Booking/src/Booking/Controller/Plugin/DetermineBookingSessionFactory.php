<?php

namespace Booking\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DetermineBookingSessionFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new DetermineBookingSession($sm->getServiceLocator()->get('User\Manager\UserSessionManager'));
    }

}