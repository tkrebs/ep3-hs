<?php

namespace Bundle\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleTable(BundleTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}