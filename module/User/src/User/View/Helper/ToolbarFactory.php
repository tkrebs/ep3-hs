<?php

namespace User\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ToolbarFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Toolbar($sm->getServiceLocator()->get('User\Manager\UserSessionManager'));
    }

}