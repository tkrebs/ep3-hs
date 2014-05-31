<?php

namespace Bill\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BillItemTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillItemTable(BillItemTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}