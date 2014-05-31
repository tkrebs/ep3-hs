<?php

namespace Bundle\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleNightTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleNightTable(BundleNightTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}