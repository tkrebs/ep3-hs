<?php

namespace Bill\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BillNightTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillNightTable(BillNightTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}