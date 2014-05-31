<?php

namespace Bill\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BillPreviewFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillPreview(
            $sm->getServiceLocator()->get('Bill\Manager\BillManager'),
            $sm->getServiceLocator()->get('Bill\Manager\BillItemManager'),
            $sm->getServiceLocator()->get('Bill\Manager\BillNightManager'));
    }

}