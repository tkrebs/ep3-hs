<?php

namespace Calendar\Controller\Plugin;

use DateTime;
use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ValidateDateArrival extends AbstractPlugin
{

    public function __invoke(DateTime $dateArrival, $strict = false)
    {
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        if ($dateArrival <= $today) {
            if ($strict) {
                throw new RuntimeException('Arrival date cannot be today');
            }

            $dateArrival = new DateTime();
            $dateArrival->modify('+1 days');
        }

        return $dateArrival;
    }

}