<?php

namespace Booking\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BookingPreviewFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingPreview(
            $sm->getServiceLocator()->get('Room\Manager\RoomManager'),
            $sm->getServiceLocator()->get('User\Manager\UserManager'));
    }

}