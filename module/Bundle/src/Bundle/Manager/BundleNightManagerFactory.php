<?php

namespace Bundle\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleNightManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleNightManager($sm->get('Bundle\Table\BundleNightTable'));
    }

}