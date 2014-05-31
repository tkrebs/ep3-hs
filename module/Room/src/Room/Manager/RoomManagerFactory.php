<?php

namespace Room\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoomManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new RoomManager(
            $sm->get('Room\Table\RoomTable'),
            $sm->get('Room\Table\RoomMetaTable'));
    }

}