<?php

namespace Calendar\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineCheck extends AbstractPlugin
{

    public function __invoke()
    {
        $controller = $this->getController();

        $check = $controller->params()->fromQuery('check');

        if ($check && $check == 'true') {
            return true;
        } else {
            return false;
        }
    }

}