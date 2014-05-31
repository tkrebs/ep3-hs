<?php

namespace Backend\Controller;

use DateTime;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    public function dashboardAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $dateStart = new DateTime($this->params()->fromQuery('date-start'));

        $dateWeeks = $this->params()->fromQuery('date-weeks', 4);

        $dateWalker = clone $dateStart;

        $dateIntervals = array();

        for ($i = 0; $i < $dateWeeks; $i++) {
            $dateStartInterval = clone $dateWalker;
            $dateStartInterval->setTime(0, 0, 0);

            $dateEndInterval = clone $dateStartInterval;
            $dateEndInterval->modify('+7 days');

            $dateIntervals[] = array(
                'dateStart' => $dateStartInterval,
                'dateEnd' => $dateEndInterval,
            );

            $dateWalker = clone $dateEndInterval;
        }

        $bookings = $bookingManager->getBetween($dateStart, $dateWalker, null, null, true);
        $rooms = $roomManager->getBy(array('status' => 'enabled'), 'rnr ASC');

        $userManager->getByBookings($bookings);

        return array(
            'dateWeeks' => $dateWeeks,
            'dateIntervals' => $dateIntervals,
            'bookings' => $bookings,
            'rooms' => $rooms,
        );
    }

}