<?php

namespace Booking\Controller\Plugin;

use Bundle\Entity\Bundle;
use DateTime;
use Room\Entity\Room;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class RedirectToBookingRoute extends AbstractPlugin
{

    public function __invoke($route, DateTime $dateArrival, DateTime $dateDeparture, $capacity, Room $room, Bundle $bundle = null, $bundleItemsCode = null)
    {
        $query = array(
            'date-arrival' => $dateArrival->format('Y-m-d'),
            'date-departure' => $dateDeparture->format('Y-m-d'),
            'capacity' => $capacity,
            'room' => $room->need('rid'),
        );

        if ($bundle) {
            $query['bundle'] = $bundle->need('bid');

            if ($bundleItemsCode) {
                $query['bundle-items'] = $bundleItemsCode;
            }
        }

        return $this->getController()->redirect()->toRoute($route, [], ['query' => $query]);
    }

}