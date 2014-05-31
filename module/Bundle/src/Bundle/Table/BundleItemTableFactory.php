<?php

namespace Bundle\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleItemTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleItemTable(BundleItemTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}