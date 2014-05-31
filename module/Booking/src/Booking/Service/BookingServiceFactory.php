<?php

namespace Booking\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BookingServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $bookingService = new BookingService(
            $sm->get('Bill\Manager\BillManager'),
            $sm->get('Bill\Manager\BillItemManager'),
            $sm->get('Bill\Manager\BillNightManager'),
            $sm->get('Booking\Manager\BookingManager'),
            $sm->get('Bundle\Manager\BundleItemManager'),
            $sm->get('Bundle\Manager\BundleNightManager'),
            $sm->get('User\Manager\UserManager'),
            $sm->get('Zend\Db\Adapter\Adapter'));

        $bookingService->getEventManager()->attach(
            $sm->get('Booking\Service\Listener\ConfirmationListener'));

        $bookingService->getEventManager()->attach(
            $sm->get('Booking\Service\Listener\NotificationListener'));

        return $bookingService;
    }

}