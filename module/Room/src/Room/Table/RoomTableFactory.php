<?php

namespace Room\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoomTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new RoomTable(RoomTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}