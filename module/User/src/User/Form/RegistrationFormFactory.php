<?php

namespace User\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegistrationFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $serviceManager = $sm->getServiceLocator();
        $configManager = $serviceManager->get('Base\Manager\ConfigManager');

        $locale = $configManager->need('i18n.locale');

        return new RegistrationForm(
            $serviceManager->get('User\Manager\UserManager'),
            $serviceManager->get('User\Service\CountryService'),
            $locale);
    }

}