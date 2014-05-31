<?php

namespace Backend\Form\User;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditUserFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditUserForm(
            $sm->getServiceLocator()->get('User\Manager\UserManager'),
            $sm->getServiceLocator()->get('User\Service\CountryService'));
    }

}