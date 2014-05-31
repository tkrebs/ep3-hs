<?php

namespace Product\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ProductManager(
            $sm->get('Product\Table\ProductTable'),
            $sm->get('Product\Table\ProductMetaTable'));
    }

}