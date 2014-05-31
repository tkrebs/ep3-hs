<?php

namespace Booking\Controller\Plugin;

use RuntimeException;
use User\Manager\UserSessionManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineBookingSession extends AbstractPlugin
{

    protected $userSessionManager;

    public function __construct(UserSessionManager $userSessionManager)
    {
        $this->userSessionManager = $userSessionManager;
    }

    public function __invoke()
    {
        $bookingSession = $this->userSessionManager->getSessionContainer('BookingSession');

        $registrationData = $bookingSession->registrationData;

        if (! ($registrationData && is_array($registrationData))) {
            throw new RuntimeException('No registration data available');
        }

        return $bookingSession;
    }

}