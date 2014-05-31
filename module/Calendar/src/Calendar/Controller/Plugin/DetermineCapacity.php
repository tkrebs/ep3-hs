<?php

namespace Calendar\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineCapacity extends AbstractPlugin
{

    public function __invoke($capacityMax = null)
    {
        $controller = $this->getController();

        $passedCapacity = $controller->params()->fromQuery('capacity');

        if (! $passedCapacity) {
            if ($controller->cookie()->get('capacity')) {
                $passedCapacity = $controller->cookie()->get('capacity');
            } else {
                $passedCapacity = 1;
            }
        }

        if (! is_numeric($passedCapacity)) {
            $passedCapacity = 1;
        }

        if ($capacityMax && $capacityMax < $passedCapacity) {
            $passedCapacity = $capacityMax;
        }

        $controller->cookie()->set('capacity', $passedCapacity);

        return $passedCapacity;
    }

}