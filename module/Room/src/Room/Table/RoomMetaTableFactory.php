<?php

namespace Room\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoomMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new RoomMetaTable(RoomMetaTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}