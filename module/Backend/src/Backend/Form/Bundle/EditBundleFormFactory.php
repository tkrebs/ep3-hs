<?php

namespace Backend\Form\Bundle;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditBundleFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditBundleForm($sm->getServiceLocator()->get('Room\Manager\RoomManager'));
    }

}