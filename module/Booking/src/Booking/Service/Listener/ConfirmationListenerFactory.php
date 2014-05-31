<?php

namespace Booking\Service\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfirmationListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ConfirmationListener(
            $sm->get('Base\Manager\OptionManager'),
            $sm->get('ViewHelperManager')->get('DateFormat'),
            $sm->get('User\Service\MailService'));
    }

}