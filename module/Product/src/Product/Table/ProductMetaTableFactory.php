<?php

namespace Product\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ProductMetaTable(ProductMetaTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}