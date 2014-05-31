<?php

namespace Bundle\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleItemManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleItemManager($sm->get('Bundle\Table\BundleItemTable'));
    }

}