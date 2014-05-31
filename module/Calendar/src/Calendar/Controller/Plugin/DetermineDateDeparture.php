<?php

namespace Calendar\Controller\Plugin;

use DateTime;
use Exception;
use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineDateDeparture extends AbstractPlugin
{

    public function __invoke()
    {
        $controller = $this->getController();

        try {
            $passedDate = $controller->params()->fromQuery('date-departure');

            if (! $passedDate) {
                if ($controller->cookie()->get('date-departure')) {
                    $passedDate = $controller->cookie()->get('date-departure');
                } else {
                    $passedDate = 'now + 6 days';
                }
            }

            $dateDeparture = new DateTime($passedDate);
            $dateDeparture->setTime(0, 0, 0);

            if ($dateDeparture) {
                $controller->cookie()->set('date-departure', $dateDeparture->format('Y-m-d'));
            }

            return $dateDeparture;
        } catch (Exception $e) {
            throw new RuntimeException('The passed departure date is invalid');
        }
    }

}