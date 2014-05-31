<?php

namespace Backend\Form\Bundle\Item;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditBundleItemFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditBundleItemForm($sm->getServiceLocator()->get('Product\Manager\ProductManager'));
    }

}