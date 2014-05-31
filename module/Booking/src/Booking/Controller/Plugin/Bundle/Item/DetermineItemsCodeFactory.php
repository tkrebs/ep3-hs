<?php

namespace Booking\Controller\Plugin\Bundle\Item;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DetermineItemsCodeFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new DetermineItemsCode($sm->getServiceLocator()->get('Bundle\Manager\BundleItemManager'));
    }

}