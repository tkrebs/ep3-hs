<?php

namespace Bundle\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleMetaTable(BundleMetaTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}