<?php

namespace Booking\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegistrationFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $serviceManager = $sm->getServiceLocator();
        $configManager = $serviceManager->get('Base\Manager\ConfigManager');
        $optionManager = $serviceManager->get('Base\Manager\OptionManager');
        $countryService = $serviceManager->get('User\Service\CountryService');

        $locale = $configManager->need('i18n.locale');

        return new RegistrationForm($optionManager, $countryService, $locale);
    }

}