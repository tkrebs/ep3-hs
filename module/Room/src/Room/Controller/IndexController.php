<?php

namespace Room\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $serviceManager = $this->getServiceLocator();
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $rid = $this->params()->fromRoute('rid');

        $room = $roomManager->get($rid);

        return array(
            'room' => $room,
        );
    }

}