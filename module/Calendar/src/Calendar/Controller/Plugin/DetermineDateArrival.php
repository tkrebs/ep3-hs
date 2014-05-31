<?php

namespace Calendar\Controller\Plugin;

use DateTime;
use Exception;
use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineDateArrival extends AbstractPlugin
{

    public function __invoke()
    {
        $controller = $this->getController();

        try {
            $passedDate = $controller->params()->fromQuery('date-arrival');

            if (! $passedDate) {
                if ($controller->cookie()->get('date-arrival')) {
                    $passedDate = $controller->cookie()->get('date-arrival');
                } else {
                    $passedDate = 'now + 1 days';
                }
            }

            $dateArrival = new DateTime($passedDate);
            $dateArrival->setTime(0, 0, 0);

            if ($dateArrival) {
                $controller->cookie()->set('date-arrival', $dateArrival->format('Y-m-d'));
            }

            return $dateArrival;
        } catch (Exception $e) {
            throw new RuntimeException('The passed arrival date is invalid');
        }
    }

}