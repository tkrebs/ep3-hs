<?php

namespace Bundle\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleManager(
            $sm->get('Bundle\Table\BundleTable'),
            $sm->get('Bundle\Table\BundleMetaTable'));
    }

}