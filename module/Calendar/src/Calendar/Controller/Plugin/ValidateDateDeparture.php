<?php

namespace Calendar\Controller\Plugin;

use DateTime;
use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ValidateDateDeparture extends AbstractPlugin
{

    public function __invoke(DateTime $dateDeparture, DateTime $dateArrival, $strict = false)
    {
        if ($dateArrival >= $dateDeparture) {
            if ($strict) {
                throw new RuntimeException('Arrival date must be earlier than departure');
            }

            $dateDeparture = clone $dateArrival;
            $dateDeparture->modify('+1 days');
        }

        return $dateDeparture;
    }

}