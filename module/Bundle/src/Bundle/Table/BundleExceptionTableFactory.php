<?php

namespace Bundle\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleExceptionTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleExceptionTable(BundleExceptionTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}