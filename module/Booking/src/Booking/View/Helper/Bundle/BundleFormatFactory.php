<?php

namespace Booking\View\Helper\Bundle;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BundleFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BundleFormat(
            $sm->getServiceLocator()->get('Bundle\Manager\BundleItemManager'),
            $sm->getServiceLocator()->get('Bundle\Manager\BundleNightManager'),
            $sm->getServiceLocator()->get('Product\Manager\ProductManager'));
    }

}