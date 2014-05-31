<?php

namespace Calendar\Controller;

use Zend\Db\Sql\Predicate\Expression;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $serviceManager = $this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $dateArrival = $this->validateDateArrival($this->determineDateArrival());
        $dateDeparture = $this->validateDateDeparture($this->determineDateDeparture(), $dateArrival);

        $capacityMax = $roomManager->getCapacityMax();
        $capacity = $this->determineCapacity($capacityMax);

        $check = $this->determineCheck();

        if ($check) {
            $rooms = $roomManager->getBy(array('status' => 'enabled', new Expression('capacity >= ' . $capacity)), 'rnr ASC');

            $roomsFree = array();
            $roomsBookable = array();

            foreach ($rooms as $rid => $room) {
                $bookings = $bookingManager->getBetween($dateArrival, $dateDeparture, $room);

                if (! $bookings) {
                    $bundles = $bundleManager->getByBooking($dateArrival, $dateDeparture, $room);

                    if ($bundles) {
                        $roomsBookable[$rid] = $room;
                    }

                    $roomsFree[$rid] = $room;
                }
            }
        } else {
            $roomsFree = null;
            $roomsBookable = null;
        }

        return array(
            'dateArrival' => $dateArrival,
            'dateDeparture' => $dateDeparture,
            'capacityMax' => $capacityMax,
            'capacity' => $capacity,
            'roomsFree' => $roomsFree,
            'roomsBookable' => $roomsBookable,
        );
    }

    public function snippetJsAction()
    {
        $serviceManager = $this->getServiceLocator();
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $capacityMax = $roomManager->getCapacityMax();

        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('content-type', 'text/javascript; charset=UTF-8');

        $viewModel = new ViewModel(array(
            'capacityMax' => $capacityMax,
        ));

        $viewModel->setTerminal(true);

        return $viewModel;
    }

}