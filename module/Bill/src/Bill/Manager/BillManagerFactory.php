<?php

namespace Bill\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BillManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillManager(
            $sm->get('Bill\Table\BillTable'),
            $sm->get('Bill\Table\BillMetaTable'));
    }

}