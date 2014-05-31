<?php

namespace Booking\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DetermineBookingParamsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new DetermineBookingParams(
            $sm->getServiceLocator()->get('Booking\Manager\BookingManager'),
            $sm->getServiceLocator()->get('Bundle\Manager\BundleManager'),
            $sm->getServiceLocator()->get('Bundle\Manager\BundleItemManager'),
            $sm->getServiceLocator()->get('Bundle\Manager\BundleNightManager'),
            $sm->getServiceLocator()->get('Product\Manager\ProductManager'),
            $sm->getServiceLocator()->get('Room\Manager\RoomManager'));
    }

}