<?php

namespace Product\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ProductTable(ProductTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}