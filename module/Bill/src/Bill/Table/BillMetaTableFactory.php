<?php

namespace Bill\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BillMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillMetaTable(BillMetaTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}