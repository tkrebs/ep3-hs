<?php

namespace Backend\Form\Booking;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditBookingFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditBookingForm($sm->getServiceLocator()->get('Room\Manager\RoomManager'));
    }

}